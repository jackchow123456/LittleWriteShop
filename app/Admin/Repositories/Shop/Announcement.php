<?php

namespace App\Admin\Repositories\Shop;

use Dcat\Admin\Repositories\EloquentRepository;
use App\Models\Shop\Announcement as AnnouncementModel;

class Announcement extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = AnnouncementModel::class;
}
