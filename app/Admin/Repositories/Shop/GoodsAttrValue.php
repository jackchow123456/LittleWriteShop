<?php
/**
 * Created by PhpStorm.
 * User: zhouminjie
 * Date: 2020-03-18
 * Time: 16:01
 */

namespace App\Admin\Repositories\Shop;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\Shop\GoodsAttrValue as GoodsAttrValueModel;

class GoodsAttrValue extends EloquentRepository
{
    protected $eloquentClass = GoodsAttrValueModel::class;
}