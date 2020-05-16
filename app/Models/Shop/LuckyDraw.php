<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\HasDateTimeFormatter;

class LuckyDraw extends Model
{
    use HasDateTimeFormatter;

    protected $connection = 'shop';
    protected $table = 'lucky_draw';
    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function setStartTimeAttribute($val)
    {
        $this->attributes['start_time'] = strtotime($val);
    }

    public function setEndTimeAttribute($val)
    {
        $this->attributes['end_time'] = strtotime($val);
    }

    public function goods()
    {
        return $this->hasMany(LuckyDrawGoods::class, 'ld_id');
    }

}
