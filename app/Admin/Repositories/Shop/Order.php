<?php

namespace App\Admin\Repositories\Shop;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\Shop\Order as OrderModel;

class Order extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = OrderModel::class;
}
