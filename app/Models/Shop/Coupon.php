<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\HasDateTimeFormatter;

class Coupon extends Model
{
    use HasDateTimeFormatter;

    protected $connection = 'shop';
    protected $table = 'coupon';
    protected $guarded = [];

    protected $dates = [
        'start_time',
        'end_time',
    ];

    public function setTypeValueAttribute($key)
    {
        is_null($key) ? $this->attributes['type_value'] = '' : $this->attributes['type_value'] = $key;
    }


}
