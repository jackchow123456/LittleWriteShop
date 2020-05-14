<?php

namespace App\Admin\Controllers\Shop;

use App\Models\Shop\Ad;
use App\Models\Shop\Store;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;

class AdController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'banner管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Ad());

        $grid->column('id', __('Id'));
        $grid->column('name', __('名称'));
        $grid->store_id('所属店铺')->display(function ($storeId) {
            return Store::find($storeId)->name;
        });
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
        $builder = Ad::with('banners');
        return Form::make($builder, function (Form $form) {
            $form->text('name', __('名称'));
            $form->hidden('store_id', __('店铺id'))->readonly()->value(getStoreId());
            $form->hasMany('banners', 'banners', function (Form\NestedForm $form) {
                $form->image('image');
                $form->number('sort')->min(0);
                $form->textarea('message');
            });
        });

    }


}
