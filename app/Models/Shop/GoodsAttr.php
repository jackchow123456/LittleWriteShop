<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\HasDateTimeFormatter;

class GoodsAttr extends Model
{
    use HasDateTimeFormatter;

    protected $connection = 'shop';
    protected $table = 'goods_attr';
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * 销售属性值列表
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(GoodsAttrValue::class, 'goods_attr_id');
    }


}
