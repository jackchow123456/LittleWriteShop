<?php
/**
 * Created by PhpStorm.
 * User: zhouminjie
 * Date: 2020-05-09
 * Time: 11:59
 */

namespace App\Admin\Extensions\Tools;

use App\Admin\Forms\ConfirmPayForm;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid\RowAction;

class ConfirmPay extends RowAction
{

    protected $title = '确认支付';

    public function render()
    {
        $id = "reset-pwd-{$this->getKey()}";

        // 模态窗
        $this->modal($id);

        return <<<HTML
<span class="grid-expand" data-toggle="modal" data-target="#{$id}">
   <a href="javascript:void(0)"><i class="feather icon-maximize"></i> {$this->title}</a>
</span>
HTML;
    }


    protected function modal($id)
    {
        // 工具表单
        $form = new ConfirmPayForm($this->getKey(), $this->row->get('order_sn'), $this->row->get('order_amount'));

        // 在弹窗标题处显示当前行的用户名
        $order_sn = $this->row->order_sn;

        // 刷新页面时移除模态窗遮罩层
        Admin::script('Dcat.onPjaxComplete(function () {
            $(".modal-backdrop").remove();
        }, true)');

        // 通过 Admin::html 方法设置模态窗HTML
        Admin::html(
            <<<HTML
<div class="modal fade" id="{$id}">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{$this->title} - {$order_sn}</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        {$form->render()}
      </div>
    </div>
  </div>
</div>
HTML
        );
    }

}