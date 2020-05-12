<?php

namespace App\Admin\Controllers\Shop;

use App\Admin\Repositories\Shop\Announcement;
use App\Models\Shop\Store;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class AnnouncementController extends AdminController
{

    protected $title = '公告管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Announcement(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->name;
            $grid->store_id('所属店铺')->display(function ($storeId) {
                return Store::find($storeId)->name;
            });
            $grid->status('状态')->switch();
            $grid->created_at;
            $grid->updated_at->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Announcement(), function (Show $show) {
            $show->id;
            $show->name;
            $show->status('状态')->using(['0' => '停用', '1' => '启动']);
            $show->content('内容')->unescape();
            $show->created_at;
            $show->updated_at;
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Announcement(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('publisher', '发布者')->value(Admin::user()->name)->disable();
            $form->hidden('publisher_id', '发布者')->value(Admin::user()->id);
            $form->hidden('store_id', __('店铺id'))->readonly()->value(getStoreId());
            $form->editor('content', __('内容'));
            $form->switch('status', __('状态'))->default(1);
            $form->switch('is_top', __('是否置顶'))->default(0);
            $form->number('sort_order', __('排序'))->default(0);

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
