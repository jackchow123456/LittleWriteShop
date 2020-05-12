<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Dcat\Admin\Traits\ModelTree;

class GoodsCategory extends Model
{
    use HasDateTimeFormatter;
    use ModelTree;

    protected $orderColumn = 'sort';
    protected $connection = 'shop';
    protected $table = 'goods_category';

    protected $guarded = [];

    public function __construct(array $attributes = [], $is_appends = true)
    {
        parent::__construct($attributes);
    }

    /**
     * Get options for Select field in form.
     *
     * @param \Closure|null $closure
     *
     * @return array
     */
    public static function selectOptions(\Closure $closure = null)
    {
        $options = (new static())->withQuery($closure)->buildSelectOptions();

        return collect($options)->all();
    }
}
