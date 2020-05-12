<?php

namespace App\Admin\Controllers\Shop;

use Dcat\Admin\Controllers\AdminController;
use App\Models\Shop\GoodsCategory;
use Dcat\Admin\Form;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Tree;

class GoodsCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品分类管理';

    public function index(Content $content)
    {
        return $content->header('商品分类管理')
            ->body(function (Row $row) {
                $tree = new Tree(new GoodsCategory);
                $tree->disableCreateButton();
                $tree->disableEditButton();
                $row->column(12, $tree);
            });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Form::make(new GoodsCategory(), function (Form $form) {
//            $permissionTable = config('admin.database.permissions_table');
//            $connection = config('admin.database.connection');
//            $permissionModel = config('admin.database.permissions_model');
//
//            $id = $form->getKey();

            $form->display('id', 'ID');

            $form->select('parent_id', trans('admin.parent_id'))
                ->options(GoodsCategory::selectOptions())
                ->saving(function ($v) {
                    return (int) $v;
                });

//            $form->text('slug', trans('admin.slug'))
//                ->required()
//                ->creationRules(['required', "unique:{$connection}.{$permissionTable}"])
//                ->updateRules(['required', "unique:{$connection}.{$permissionTable},slug,$id"]);
            $form->text('title', trans('admin.name'))->required();

//            $form->multipleSelect('http_method', trans('admin.http.method'))
//                ->options($this->getHttpMethodsOptions())
//                ->help(trans('admin.all_methods_if_empty'));
//
//            $form->tags('http_path', trans('admin.http.path'))
//                ->options($this->getRoutes());

            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));

            $form->disableViewButton();
            $form->disableViewCheck();
        });
    }

}
