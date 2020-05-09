<?php

namespace App\Admin\Repositories\Shop;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\Shop\Store as ShopStoreModel;

class Store extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = ShopStoreModel::class;
}
