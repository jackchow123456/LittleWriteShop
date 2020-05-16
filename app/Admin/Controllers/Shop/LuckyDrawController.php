<?php

namespace App\Admin\Controllers\Shop;

use App\Admin\Extensions\Show\GoodsSelector;
use App\Models\Shop\Coupon;
use App\Models\Shop\LuckyDraw;
use App\Models\Shop\Store;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;

class LuckyDrawController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '转盘管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LuckyDraw());

        $grid->column('id', __('Id'));
        $grid->column('name', __('名称'));
        $grid->store_id('所属店铺')->display(function ($storeId) {
            return Store::find($storeId)->name;
        });

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
        $builder = LuckyDraw::with('goods');
        return Form::make($builder, function (Form $form) {
            $form->text('name', __('名称'));
            $form->hidden('store_id', __('店铺id'))->readonly()->value(getStoreId());
            $form->datetimeRange('start_time', 'end_time', __('上架时间'));
            $form->switch('status', __('状态'))->default(1);

            $form->hasMany('goods', '奖励设置', function (Form\NestedForm $form) {
                $form->select('type','奖励类型')->width(3)->options([
//                    '商品' => '商品',
                    '现金券' => '现金券',
                    '现金' => '现金',
                    '多谢惠顾' => '多谢惠顾',
                    '积分' => '积分',
                ]);
                $form->text('type_value', '类型值')->width(3);
                $form->hidden('number', '奖励数量')->width(3)->required()->value(1);
                $form->number('stock', '奖励库存')->width(3)->required()->min(0);
            })->mode('table');
        });

    }

}
