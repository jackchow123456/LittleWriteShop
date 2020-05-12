<?php

namespace App\Admin\Controllers\Shop;

use App\Admin\Extensions\Show\DeliveryTracking;
use App\Admin\Extensions\Tools\ConfirmDelivery;
use App\Admin\Extensions\Tools\ConfirmPay;
use App\Admin\Extensions\Tools\OrderStatus;
use App\Models\Shop\ExpressQuery;
use App\Models\Shop\Order;
use App\Admin\Repositories\Shop\Order as OrderRepository;
use App\Models\Shop\Store;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Dcat\Admin\Widgets\Box;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderRepository(['user', 'address']));

        $grid->filter(function ($filter) {

            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->equal('order_id', '订单Id');
            $filter->equal('order_sn', '订单编号');

            $orderStatus = (new Order())->getOrderStatusDesc();
            $filter->equal('order_status', '订单状态')->radio($orderStatus);
            $filter->between('created_at', '创建时间')->datetime();
        });

        $grid->column('order_id', __('Id'));
        $grid->column('order_sn', __('订单编号'));
        $grid->store_id('所属店铺')->display(function ($storeId) {
            return Store::find($storeId)->name ?? '';
        });
        $grid->column('user.name', __('购买人'));
        $grid->column('address.phone', __('联系电话'));
        $grid->column('address_detail', __('收货地址'));

        $that = $this;


        $grid->column('order_status_desc', __('订单状态'))->if(function () {
            return $this->order_status == Order::ORDER_STATUS['PAYED'] ? true : false;
        })->modal('支付凭证', function () use ($that) {
            return new Show($this->order_id, new OrderRepository(['user', 'address']), function (Show $show) use ($that) {
                $data = json_decode($this->pay_depend, true);
                $show->pay_image('交易凭证')->value($data['image'] ?? '')->image();
                $show->pay_comment('备注')->value($data['pay_comment']);
                $show->pay_time('付款时间')->as(function ($time) {
                    return date('Y-m-d H:i:s', $time);
                });
                $show->panel()->title('');
                $show->disableDeleteButton();
                $show->disableEditButton();
                $show->disableListButton();
            });
        });


        $grid->column('shipping_status_desc', __('发货状态'))->if(function () {
            return $this->shipping_status == Order::SHIPPING_STATUS['SHIPPED'] ? true : false;
        })->modal('发货状态', function () use ($that) {
            return new Show($this->order_id, new OrderRepository(['user', 'address']), function (Show $show) use ($that) {
                $show->delivery_name('快递公司');
                $show->delivery_no('快递单号');
                $show->shipping_time('发货时间')->as(function ($time) {
                    return date('Y-m-d H:i:s', $time);
                });
                if ($expressResult = $that->queryExpress($this->delivery_name, $this->delivery_no)) {
                    $show->column('快递跟踪')->unescape()->DeliveryTracking($expressResult);
                }
                $show->panel()->title('');
                $show->disableDeleteButton();
                $show->disableEditButton();
                $show->disableListButton();
            });
        });
        $grid->column('order_amount', __('订单金额'));
//        $grid->column('payment', __('支付方式'));


        $grid->column('created_at', __('创建时间'));
        $grid->model()->orderBy('order_id', 'desc');

        if (in_array($type = request()->get('type'), ['notPay', 'notShipping'])) {
            if ($type == 'notPay')
                $grid->model()->where('order_status', Order::ORDER_STATUS['NO_PAY']);
            else
                $grid->model()->where('order_status', Order::ORDER_STATUS['PAYED'])->where('shipping_status', Order::SHIPPING_STATUS['UN_SHIPPED']);
        }

        $grid->tools(new OrderStatus());
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->row->get('order_status') == Order::ORDER_STATUS['NO_PAY']) {
                $actions->append(new ConfirmPay);
            }

            if ($actions->row->get('order_status') == Order::ORDER_STATUS['PAYED'] and $actions->row->get('shipping_status') == Order::SHIPPING_STATUS['UN_SHIPPED']) {
                $actions->append(new ConfirmDelivery);
            }
        });

        $grid->disableCreateButton();
        $grid->disableEditButton();

        return $grid;
    }

    public function show($id, Content $content)
    {
        return $content->header('Order')
            ->description('详情')
            ->body(new Show($id, new OrderRepository(['user', 'address']), function (Show $show) {

                // 基本信息
                $show->order_id('订单Id');
                $show->order_sn('订单编号');
                $show->user('购买用户')->as(function ($user) {
                    return $user['name'];
                });
                $show->address('联系电话')->as(function ($address) {
                    return $address['phone'];
                });
                $show->address_detail('收货地址');
                $show->order_amount('订单金额');
                $show->order_status_desc('订单状态');
                $show->shipping_status_desc('发货状态');
                $show->payment('付款方式');
                $show->created_at('创建时间');

                // 快递信息
                $model = $show->model();
                if ($model->shipping_status == Order::SHIPPING_STATUS['SHIPPED']) {
                    $show->delivery_name('快递名称');
                    $show->delivery_no('快递单号');
                    $expressResult = $this->queryExpress($model->delivery_name, $model->delivery_no);
                    if ($expressResult) {
                        $show->column('快递跟踪')->unescape()->DeliveryTracking($expressResult);
                    }
                }

                // 操作拦
                $model->order_status == Order::ORDER_STATUS['PAYED'] && $model->shipping_status == Order::SHIPPING_STATUS['UN_SHIPPED'] && $show->column('操作')->unescape()->DeliveryForm($model->order_id);

                $show->goods('商品信息', function ($goods) {
                    $goods->setResource('/admin/store/goods');
                    $goods->rec_id();
                    $goods->goods_name('商品名称');
                    $goods->face_img('商品图')->image();
                    $goods->goods_price('商品单价');
                    $goods->buy_num('购买数量');
                });
            }));
    }

    // 查询物流
    public function queryExpress($shippingCode, $queryNo)
    {
        $arr = [
            '申通快递' => 'STO',
            '顺丰快递' => 'SFEXPRESS',
            '圆通快递' => 'YTO',
            '韵达快递' => 'YUNDA',
            '中通快递' => 'ZTO',
        ];

        if (!isset($arr[$shippingCode])) return [];

        $record = ExpressQuery::query()->updateOrCreate([
            'express_name' => $shippingCode,
            'express_no' => $queryNo,
        ]);

        if ($record && $record->express_status == '已签收') return $record->data;
        if ($record && $record->data && strtotime($record->updated_at) > strtotime(date('Y-m-d'))) return $record->data;

        $shippingCode = $arr[$shippingCode];

        Log::info('开始进行快递接口查询', []);
        $uri = "https://kdwlcxf.market.alicloudapi.com/kdwlcx";
        $appCode = env('EXPRESS_APP_CODE', '');
        $response = (new Client())->request('GET', $uri, [
            'headers' => [
                'Authorization' => 'APPCODE ' . $appCode
            ],
            'query' => [
                'no' => $queryNo,
                'type' => $shippingCode,
            ],
        ]);

        $content = $response->getBody()->getContents();
        $result = json_decode($content, true);

        $string = data_get($result, 'result.list.0.status', '');
        $expressStatus = '未签收';
        if (strpos($string, '签收') !== false) {
            $expressStatus = '已签收';
        }
        $record->status = $result['status'] == 0 ? '成功' : '失败';
        $record->code = $result['status'];
        $record->data = $result;
        $record->express_status = $expressStatus;
        $record->save();

        return $result;
    }

    // 订单发货
    public function delivery(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);

        $orderId = $request->input('order_id');
        $deliveryName = $request->input('delivery_name') ?: '';
        $deliveryNo = $request->input('delivery_no') ?: '';

        $order = Order::findOrFail($orderId);
        $order->shipping_status = Order::SHIPPING_STATUS['SHIPPED'];
        $order->delivery_name = $deliveryName;
        $order->delivery_no = $deliveryNo;
        $order->shipping_time = time();
        $order->save();

        return successMsg([]);
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order());
        $form->image('image', __('商品图'));
        return $form;
    }
}
