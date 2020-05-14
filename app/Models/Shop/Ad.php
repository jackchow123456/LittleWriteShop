<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\HasDateTimeFormatter;

class Ad extends Model
{
    use HasDateTimeFormatter;

    protected $connection = 'shop';
    protected $table = 'ad';
    protected $guarded = [];

    public function banners()
    {
        return $this->hasMany(AdImage::class, 'ad_id');
    }
}
