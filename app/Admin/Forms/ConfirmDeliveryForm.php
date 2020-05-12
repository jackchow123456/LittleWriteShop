<?php

namespace App\Admin\Forms;

use App\Models\Shop\Order;
use Dcat\Admin\Widgets\Form;
use Symfony\Component\HttpFoundation\Response;

class ConfirmDeliveryForm extends Form
{
    // 增加一个自定义属性保存用户ID
    protected $id;
    protected $orderSn;

    // 构造方法的参数必须设置默认值
    public function __construct($id = null, $orderSn = null)
    {
        $this->id = $id;
        $this->orderSn = $orderSn;
        parent::__construct();
    }

    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return Response
     */
    public function handle(array $input)
    {
        $deliveryName = $input['delivery_name'];
        $deliveryNo = $input['delivery_no'];
        // return $this->error('Your error message.');

        $id = $input['id'] ?? null;
        $order = Order::query()->findOrFail($id);
        $order->shipping_status = Order::SHIPPING_STATUS['SHIPPED'];
        $order->delivery_name = $deliveryName;
        $order->delivery_no = $deliveryNo;
        $order->shipping_time = time();
        $order->save();

        return $this->success('发货成功.', route('order.index'));
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->hidden('id')->value($this->id);
        $this->text('order_sn', '订单编号')->disable()->value($this->orderSn);
        $this->select('delivery_name', '快递公司')->required()->options([
            '自己送货' => '自己送货',
            '顺丰快递' => '顺丰快递',
            '韵达快递' => '韵达快递',
            '中通快递' => '中通快递',
        ])->default('自己送货');
        $this->text('delivery_no', '快递单号')->rules('nullable|min:3');
    }

    /**
     * The data of the form.
     *
     * @return array
     */
    public function default()
    {
        return [
            'name' => 'John Doe',
            'email' => 'John.Doe@gmail.com',
        ];
    }
}
