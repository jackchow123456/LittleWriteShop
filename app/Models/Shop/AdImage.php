<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class AdImage extends Model
{
    protected $connection = 'shop';
    protected $table = 'ad_image';
    protected $guarded = [];

    public function ad()
    {
        return $this->belongsTo(Ad::class,'ad_id');
    }
}
