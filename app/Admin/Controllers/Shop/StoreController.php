<?php

namespace App\Admin\Controllers\Shop;

use App\Admin\Repositories\Shop\Store;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Controllers\AdminController;

class StoreController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Store(), function (Grid $grid) {
            $grid->id->sortable();
            $grid->name;
            $grid->status('营业状态')->switch();
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
        return Show::make($id, new Store(), function (Show $show) {
            $show->id;
            $show->name;
            $show->status('营业状态')->using(['0'=>'停业','1'=>'营业']);
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
        return Form::make(new Store(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->switch('status',__('营业状态'))->default(1);

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
