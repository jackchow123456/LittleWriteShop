<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class GoodsAttrValue extends Model
{
    protected $connection = 'shop';
    protected $table = 'goods_attr_values';
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
