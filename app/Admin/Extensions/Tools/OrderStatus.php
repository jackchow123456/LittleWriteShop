<?php
/**
 * Created by PhpStorm.
 * User: zhouminjie
 * Date: 2020-05-09
 * Time: 11:59
 */

namespace App\Admin\Extensions\Tools;

use App\Models\Shop\Order;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class OrderStatus extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['type' => '_type_']);

        return <<<JS
$('.order_status').click(function () {
    
    var url = "$url".replace('_type_', $(this).val());
        
    Dcat.reload(url);
});
JS;
    }

    public function render()
    {
        Admin::script($this->script());

        $notPay = Order::query()->where('store_id', getStoreId())->where('order_status', Order::ORDER_STATUS['NO_PAY'])->count();
        $notShipping = Order::query()->where('store_id', getStoreId())->where('order_status', Order::ORDER_STATUS['PAYED'])->where('shipping_status', Order::SHIPPING_STATUS['UN_SHIPPED'])->count();

        $options = [
            'notPay' => $notPay,
            'notShipping' => $notShipping,
        ];

        return view('admin.tools.order_status', compact('options'));
    }
}