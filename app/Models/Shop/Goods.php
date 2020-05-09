<?php

namespace App\Models\Shop;

use App\Admin\Repositories\Shop\GoodsRepository;
use Dcat\Admin\Admin;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $connection= 'shop';
    protected $guarded = [];

    protected $appends = [
        'sku', 'me', 'image_uri'
    ];

    public function __construct(array $attributes = [], $is_appends = true)
    {
        $this->setTable(config('store.database.table_prefix') . 'goods');
        parent::__construct($attributes);
    }

    /**
     * 图片uri字段处理
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getImageUriAttribute()
    {
        return $this->attributes['image'] ? url($this->attributes['image']) : '';
    }

    /**
     * 处理sku（获取）
     *
     * @param $key
     * @return false|string
     */
    public function getSkuAttribute($key)
    {
        return (new GoodsRepository($this))->getGoodsSkuInfo();
    }

    /**
     * 处理sku（设置）
     *
     * @param $key
     */
    public function setSkuAttribute($key)
    {
        unset($this->attributes['sku']);
    }

    /**
     * 处理商品画册（获取）
     *
     * @param $key
     * @return array
     */
    public function getMeAttribute($key)
    {
        return $this->mediaCategory ? $this->mediaCategory->medias()->pluck('path')->toArray() : [];
    }

    /**
     * 处理商品画册（设置）
     *
     * @param $key
     */
    public function setMeAttribute($key)
    {
        unset($this->attributes['me']);
    }

    /**
     * 处理商品图片（设置）
     * @param $key
     */
    public function setImageAttribute($key)
    {
        $key ? $this->attributes['image'] = getSavePath($key) : $this->attributes['image'] = '';
    }

    /**
     * 产品媒体列表
     */
    public function mediaCategory()
    {
        return $this->hasOne(MediaCategory::class, 'use_id', 'id')->where('use', '商品');
    }


    /**
     * 产品 SKU
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skus()
    {
        return $this->hasMany(GoodsSku::class, 'goods_id', 'id');
    }


    /**
     * 检查是否含有规格商品
     *
     * @return bool
     */
    public function checkHasSpecGoods()
    {
        return !$this->skus()->get()->isEmpty();
    }
}
