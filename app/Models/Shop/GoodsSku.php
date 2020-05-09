<?php


namespace App\Models\Shop;


use Illuminate\Database\Eloquent\Model;

class GoodsSku extends Model
{
    protected $guarded = [];
    protected $connection= 'shop';
    protected $table= 'goods_sku';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * SKU 库存
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stock()
    {
        return $this->hasOne(GoodsSkuStock::class, 'sku_id');
    }


    public function mediaCategory()
    {
        return $this->hasOne(MediaCategory::class, 'use_id');
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class, 'id');
    }


    const status = [-1, 0, 1];
}