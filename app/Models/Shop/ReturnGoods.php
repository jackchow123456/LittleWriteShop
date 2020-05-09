<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ReturnGoods extends Model
{
    protected $guarded = [];
    protected $primaryKey = "return_id";
    protected $connection = 'shop';
    protected $table = 'return_goods';
}
