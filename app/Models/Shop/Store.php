<?php

namespace App\Models\Shop;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
	use HasDateTimeFormatter;
    protected $connection= 'shop';
}
