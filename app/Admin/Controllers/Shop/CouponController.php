<?php

namespace App\Admin\Controllers\Shop;

use App\Admin\Extensions\Show\GoodsCatSelector;
use App\Admin\Extensions\Show\GoodsSelector;
use App\Models\Shop\Ad;
use App\Models\Shop\Coupon;
use App\Models\Shop\Goods;
use App\Models\Shop\GoodsCategory;
use App\Models\Shop\Store;
use Dcat\Admin\Admin;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Illuminate\Http\Request;

class CouponController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '优惠券管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Coupon());

        $grid->column('id', __('Id'));
        $grid->column('name', __('名称'));
        $grid->store_id('所属店铺')->display(function ($storeId) {
            return Store::find($storeId)->name;
        });
        $grid->column('the_price', __('面额'));
        $grid->column('type', __('类型'));
        $grid->column('stock', __('剩余数量'));
        $grid->column('status', __('状态'))->using(['0' => '停用', '1' => '开启']);

        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('修改时间'));

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Coupon, function (Form $form) {
            $form->text('name', __('名称'));
            $form->hidden('store_id', __('店铺id'))->readonly()->value(getStoreId());
            $form->datetimeRange('start_time', 'end_time', __('上架时间'));
            $select2 = $form->select('type', '类型')->options([
                '通用' => '通用',
                '指定商品' => '指定商品',
                '指定分类' => '指定分类'
            ]);
            $form->hidden('type_value')->readonly();
            $form->currency('the_price', '面额')->symbol('￥')->required()->width(4);
            $form->number('stock', '库存')->required()->width(4);
            $form->switch('status', __('状态'))->default(1);

            (new GoodsSelector())->render($select2->getElementClassSelector(), $form->model());
        });

    }


    public function goods(Request $request)
    {
        $q = $request->get('q');
        $pageSize = $request->input('pageSize', 15);

        $list = Goods::query()->where('name', 'like', "%$q%")->orderBy('id', 'desc')->paginate($pageSize, ['id', 'name', 'price', 'image', 'created_at']);

        return ['total' => $list->total(), 'totalNotFiltered' => $list->total(), 'rows' => $list->items()];
    }

    public function goodsByIds(Request $request)
    {
        $ids = $request->get('ids');

        $result = Goods::query()->whereRaw("id in ({$ids})")->get(['id', 'name', 'price', 'image', 'created_at']);

        return $result;
    }

    public function goodsCat(Request $request)
    {
        $q = $request->get('q');
        $pageSize = $request->input('pageSize', 15);

        $list = GoodsCategory::query()->where('title', 'like', "%$q%")->orderBy('id', 'desc')->paginate($pageSize, ['id', 'title', 'created_at']);

        return ['total' => $list->total(), 'totalNotFiltered' => $list->total(), 'rows' => $list->items()];
    }

    public function goodsCatByIds(Request $request)
    {
        $ids = $request->get('ids');

        $result = GoodsCategory::query()->whereRaw("id in ({$ids})")->get(['id', 'title', 'created_at']);

        return $result;
    }

}
