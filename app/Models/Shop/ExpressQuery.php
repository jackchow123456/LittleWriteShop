<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ExpressQuery extends Model
{
    protected $guarded = [];

    protected $connection= 'shop';
    protected $table = 'express_query';

    protected $casts = [
        'data' => 'array'
    ];
}
