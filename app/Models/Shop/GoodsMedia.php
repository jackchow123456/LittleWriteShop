<?php


namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class GoodsMedia extends Model
{
    protected $guarded = [];
    protected $connection= 'shop';
    protected $table= 'goods_medias';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
