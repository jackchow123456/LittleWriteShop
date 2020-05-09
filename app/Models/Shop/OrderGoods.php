<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    protected $guarded = ['rec_id'];
    protected $primaryKey = "rec_id";
    protected $connection= 'shop';
    protected $table= 'order_goods';

    // 订单商品
    public function return_goods()
    {
        return $this->belongsTo(ReturnGoods::class, 'rec_id', 'rec_id');
    }
}
