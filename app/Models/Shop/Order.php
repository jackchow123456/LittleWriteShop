<?php

namespace App\Models\Shop;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dcat\Admin\Traits\HasDateTimeFormatter;

class Order extends Model
{
    use SoftDeletes;
    use HasDateTimeFormatter;
    protected $guarded = ['order_id'];
    protected $primaryKey = "order_id";
    protected $appends = ['order_status_desc', 'address_detail', 'shipping_status_desc'];
    protected $connection = 'shop';
    protected $table = 'order';
    //订单状态（1：未付款，2：已付款，3:已取消，4:已退货）
    protected $orderStatusDesc = ['1' => '未付款', '2' => '已付款', '3' => '已取消', '4' => '已退货', '5' => '已签收'];
    protected $shippingStatusDesc = ['1' => '未发货', '2' => '已发货'];
    protected $datas = ['deleted_at'];

    const ORDER_STATUS = [
        'NO_PAY' => 1,
        'PAYED' => 2,
        'CANCEL' => 3,
        'RETURNED' => 4,
        'CONFIRM' => 5,
    ];

    const SHIPPING_STATUS = [
        'UN_SHIPPED' => 1,
        'SHIPPED' => 2,
    ];


    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'order_id');
    }

    /**
     * 获取订单状态描述
     */
    public function getOrderStatusDescAttribute()
    {
        return $this->orderStatusDesc[$this->attributes['order_status']];
    }

    /**
     * 获取订单状态描述
     */
    public function getShippingStatusDescAttribute()
    {
        return $this->shippingStatusDesc[$this->attributes['shipping_status']];
    }

    public function getOrderStatusDesc()
    {
        return $this->orderStatusDesc;
    }


    /**
     * 获取订单状态描述
     */
    public function getAddressDetailAttribute()
    {
        if (!$this->address) return '';
        $addressDdetail = [
            $this->address->province,
            $this->address->city,
            $this->address->area,
            $this->address->town,
            $this->address->address,
        ];
        return implode(',', $addressDdetail);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function goods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id');
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }
}
