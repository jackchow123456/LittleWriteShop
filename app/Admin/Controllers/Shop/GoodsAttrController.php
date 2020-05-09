<?php

namespace App\Admin\Controllers\Shop;

use App\Admin\Repositories\Shop\GoodsAttrValue;
use App\Models\Shop\GoodsAttr;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Illuminate\Http\Request;
use App\Admin\Repositories\Shop\GoodsRepository;
use App\Admin\Resources\GoodsAttrResources;

class GoodsAttrController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品规格管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoodsAttr());

        $grid->column('id', __('Id'));
        $grid->column('name', __('规格名称'));
        $grid->column('sort', __('排序'));
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
        $builder = GoodsAttr::with('values');
        $form = new Form($builder);

        $form->text('name', __('规格名称'))->required();
        $form->number('sort', '排序');

        $form->hasMany('values', '属性值', function (Form\NestedForm $form) {
            $form->text('name');
            $form->textarea('sort');
            $form->datetime('created_at');
        });

        return $form;
    }

    // 展示
    public function show($id, Content $content)
    {
        return $content->header('商品属性')
            ->description('详情')
            ->body(Show::make($id, new GoodsAttr(), function (Show $show) {
                $show->id('ID');
                $show->name('名称');
                $show->created_at();
                $show->updated_at();

                $show->values('规格值', function ($model) {
                    $grid = new Grid(new GoodsAttrValue());
                    $grid->model()->where('goods_attr_id', $model->id);

                    // 设置路由
                    $grid->resource('goods_attr_values');

                    $grid->id();
                    $grid->name();
                    $grid->created_at();

                    return $grid;
                });
            }));


    }

    // 获取商品规格
    public function getGoodsAttr(Request $request)
    {
        $name = $request->input('name', '');

        $list = (new GoodsRepository())->getGoodsAttr($name);

        return GoodsAttrResources::collection($list);
    }

    // 创建商品规格
    public function createGoodsAttr(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => '请输入名称',
        ]);

        $name = $request->input('name');

        $result = GoodsAttr::query()->updateOrCreate([
            'name' => $name,
        ], [
            'store_id' => getStoreId(),
            'sort' => 0
        ]);

        if (!$result) {
            return failMsg('创建规格失败.');
        }

        return successMsg($result);
    }

}
