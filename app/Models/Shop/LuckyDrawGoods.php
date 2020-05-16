<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\HasDateTimeFormatter;

class LuckyDrawGoods extends Model
{
    use HasDateTimeFormatter;

    protected $connection = 'shop';
    protected $table = 'lucky_draw_goods';
    protected $guarded = [];

}
