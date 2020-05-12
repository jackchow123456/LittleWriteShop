<?php

namespace App\Admin\Forms;

use App\Models\Shop\Order;
use Dcat\Admin\Widgets\Form;
use Symfony\Component\HttpFoundation\Response;

class ConfirmPayForm extends Form
{
    // 增加一个自定义属性保存用户ID
    protected $id;
    protected $orderAmount;
    protected $orderSn;

    // 构造方法的参数必须设置默认值
    public function __construct($id = null, $orderSn = null, $orderAmount = null)
    {
        $this->id = $id;
        $this->orderSn = $orderSn;
        $this->orderAmount = $orderAmount;

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
        $data = [
            'image' => $input['image'],
            'pay_comment' => $input['pay_comment'],
        ];
        // return $this->error('Your error message.');

        $id = $input['id'] ?? null;
        $order = Order::query()->findOrFail($id);
        $order->order_status = 2;
        $order->pay_depend = json_encode($data);
        $order->pay_time = time();
        $order->save();

        return $this->success('支付成功.', route('order.index'));
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->hidden('id')->value($this->id);
        $this->text('order_sn', '订单编号')->disable()->value($this->orderSn);
        $this->text('order_amount', '订单金额')->disable()->value($this->orderAmount);
        $this->image('image', '凭证')->url('users/files');
        $this->textarea('pay_comment', '备注');
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
