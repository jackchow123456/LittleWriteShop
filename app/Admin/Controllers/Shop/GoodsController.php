<?php

namespace App\Admin\Controllers\Shop;

use App\Models\Shop\GoodsAttr;
use App\Models\Shop\GoodsCategory;
use App\Models\Shop\Store;
use Dcat\Admin\Admin;
use Dcat\Admin\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use App\Models\Shop\Goods;
use Illuminate\Http\Request;
use App\Admin\Repositories\Shop\GoodsRepository;
use App\Admin\Resources\GoodsAttrResources;

class GoodsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Goods);

        $grid->column('id', __('Id'));
        $grid->column('name', __('商品名称'));
        $grid->store_id('所属店铺')->display(function ($storeId) {
            return Store::query()->findOrFail($storeId)->name;
        });
        $grid->column('cat_id', __('商品分类'))->display(function ($catId) {
            return GoodsCategory::query()->findOrFail($catId)->title;
        });
        $grid->column('price', __('价格'));
        $grid->column('line_price', __('划线价'));
        $grid->column('stock_num', __('库存'));
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
        $form = new Form(new Goods());

        $form->tab('基本信息', function ($form) {
            $form->divider('基本信息');
            $form->text('name', __('商品名称'))->required();
            $form->hidden('user_id', __('操作人'))->readonly()->value(Admin::user()->getKey());
            $form->hidden('store_id', __('店铺id'))->readonly()->value(getStoreId());
            $form->select('cat_id', '关联分类')
                ->required()
                ->options(GoodsCategory::selectOptions());
            $form->textarea('short_description', __('分享描述'))->rows(3)->required();
            $form->image('image', __('商品图'));
            $form->dateRange('start_date', 'end_date', __('上架时间'));
            $form->currency('line_price', '划线价')->symbol('￥');
            $form->currency('cost_price', '成本价')->symbol('￥');
            $form->currency('price', '售价')->symbol('￥');
            $form->number('stock_num', '库存');
            $form->editor('content', __('内容'));

        })->tab('商品画册', function ($form) {
            $form->multipleImage('me', __('商品画册'))->help('可上传多个图片')->limit(3);
        })->tab('商品规格', function ($form) {
            $form->specific('sku', '商品规格')->options([
                'getAttrUri' => '/admin/api/getGoodsAttr',
                'createAttrUri' => '/admin/api/createGoodsAttr',
            ]);
        });

        $form->saved(function (Form $form) {
            $data = \Request::all();

            $goodsRepository = new GoodsRepository();
            isset($data['me']) && $goodsRepository->handleMeAttribute($form->builder()->field('me')->prepare($data['me']), $form->model());
            isset($data['sku']) && $goodsRepository->handleSku(json_decode(data_get($data, 'sku', '{}'), true), $form->model());

        });

        return $form;
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


    // 店铺列表
    public function cats(Request $request)
    {
        $q = $request->get('q');

        return GoodsCategory::query()->where('title', 'like', "%$q%")->paginate(null, ['id', 'title as text']);
    }
}
