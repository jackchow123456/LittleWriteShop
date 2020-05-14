<?php
/**
 * Created by PhpStorm.
 * User: zhouminjie
 * Date: 2020-05-08
 * Time: 17:06
 */

namespace App\Admin\Extensions\Show;

use Dcat\Admin\Admin;
use Dcat\Admin\Show\AbstractField;

class GoodsSelector extends AbstractField
{
    public function render($e = '', $model = null)
    {
        Admin::css('vendors/jackchow/bootstrap-table/dist/bootstrap-table.css');
        Admin::js('vendors/jackchow/bootstrap-table/dist/bootstrap-table.min.js');

        $script = $this->buildJs($e, $model);
        Admin::script($script);

    }


    public function buildGoodsHtml()
    {
        return <<<JS
        var html = '<!-- 模态框（Modal） --><div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"     aria-hidden><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">                     请选择</h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">                    ×</button></div><div class="modal-body"><form class="form-inline" id="toolbar"><div class="col-6"><div class="input-group input-group-lg"><div class="input-group-prepend"><select class="mySelect2_hhh" style="width: 80px; display: table-cell" name="key"><option value="title">商品名称</option></select></div><input type="text" class="form-control" name="value"></div></div><div class="col-4"><button type="button" class="btn btn-info btn-flat searchBtn">搜索</button></div></form></div><table id="mytable"></table><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">关闭</button><button type="button" class="btn btn-primary confirmBtn">                    提交更改</button></div></div></div></div>';
        $('.content-body').after(html);
JS;
    }


    public function buildGoodsCatHtml()
    {
        return <<<JS
        var html = '<!-- 模态框（Modal） --><div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"     aria-hidden><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">                     请选择</h4><button type="button" class="close" data-dismiss="modal" aria-hidden="true">                    ×</button></div><div class="modal-body"><form class="form-inline" id="toolbar"><div class="col-6"><div class="input-group input-group-lg"><div class="input-group-prepend"><select class="mySelect2_hhh" style="width: 80px; display: table-cell" name="key"><option value="title">分类名称</option></select></div><input type="text" class="form-control" name="value"></div></div><div class="col-4"><button type="button" class="btn btn-info btn-flat searchBtn">搜索</button></div></form></div><table id="mytable"></table><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">关闭</button><button type="button" class="btn btn-primary confirmBtn">                    提交更改</button></div></div></div></div>';
        $('.content-body').after(html);
JS;
    }


    public function buildJs($e, $model)
    {
        $basicScript = <<<JS
        function renderGoodsResult(result) {
           var html = '';
           var value = '';
           for (item of result){
                html += '<div class="rpw" style="display: inline-block;margin: 0 10px;"><img src="'+item.image+'" style="width:100px;heigth:100px;"><br><span>'+item.name+'</span></div>';
                if(value){
                    value +=  ','+item.id;
                }else{
                    value =  item.id;
                }
            } 
            
            html = '<div class="form-group row form-field goodsSelector"><div for="form-field-type-RXQLZ" class="col-md-2  text-capitalize control-label"><span>选择内容</span></div><div class="col-md-8">'+html+'</div></div>';
            
            $("input[name=type]").parent().parent().after(html);
            $("input[name=type_value]").val(value);
        }
        
        
        function renderGoodsCatResult(result) {
           var html = '';
           var value = '';
           for (item of result){
               
               if(html){
                    html +=  '，'+item.title;
                }else{
                    html =  item.title;
                }
               
                if(value){
                    value +=  ','+item.id;
                }else{
                    value =  item.id;
                }
            } 
            
            html = '<div class="form-group row form-field goodsSelector"><div for="form-field-name-dX2vD" class="col-md-2  text-capitalize control-label"><span>选择内容</span></div><div class="col-md-8"><div class="input-group"><span class="input-group-prepend"><span class="input-group-text bg-white"><i class="feather icon-edit-2"></i></span></span><input type="text" id="form-field-name-dX2vD" value="'+html+'" disabled class="form-control name" placeholder="输入 名称"></div></div></div>';
            
            $("input[name=type]").parent().parent().after(html);
            $("input[name=type_value]").val(value);
        }
        
        function clearResult() {
          $('.goodsSelector').remove();
          $("input[name=type_value]").val("");
        }
        
        
        function basic(url,column,callback) {
                  $('#myModal').modal()
                  $("#mytable").bootstrapTable({
                    locale: "zh-CN",
                    url: url,  //请求地址
                    striped: true, //是否显示行间隔色
                    pageNumber: 1, //初始化加载第一页
                    pagination: true,//是否分页
                    pageSize: 10,//单页记录数
                    sidePagination: "server",
                    clickToSelect: true,
                    pageList: "[10, 25, 50, 100]",
                    smartDisplay: false,
                    queryParams: function (params) {
        
                        var temp = {
                            key: $('#toolbar select[name="key"]').val(),
                            value: $('#toolbar input[name="value"]').val(),
                            page: this.pageNumber,
                            pageSize: params.limit  // 每页显示数量
                        };
                        return temp;
                    },
                    columns: column
                });
        
                $('.mySelect2_hhh').select2({
                    minimumResultsForSearch: Infinity
                });
                
                $('.searchBtn').off('click');
                $('.searchBtn').on('click',function () {
                    $('#mytable').bootstrapTable('refresh');
                });
        
                $('.confirmBtn').off('click');
                $('.confirmBtn').on('click',function () {
                    var result = $("#mytable").bootstrapTable('getSelections');
                    callback(result);
                })
        }
JS;

        if ($model) {
            if ($model->type == '指定商品') {
                $basicScript = <<<JS
            $basicScript
            $.ajax({
                url: '/admin/api/goodsByIds',
                method: 'GET',
                data: {'ids': "{$model->type_value}"},
                success: function (result) {
                    renderGoodsResult(result);
                },
                error: function (xhr) {
                    layer.msg('加载失败，网络异常.', {
                        scrollbar: false,
                        shade: 0.3,
                        time: 3000,
                        icon: 2
                    }, function (index) {
                        layer.closeAll();
                    });
                }
            })
JS;
            } else if ($model->type == '指定分类') {
                $basicScript = <<<JS
            $basicScript
            $.ajax({
                url: '/admin/api/goodsCatByIds',
                method: 'GET',
                data: {'ids': "{$model->type_value}"},
                success: function (result) {
                    renderGoodsCatResult(result);
                },
                error: function (xhr) {
                    layer.msg('加载失败，网络异常.', {
                        scrollbar: false,
                        shade: 0.3,
                        time: 3000,
                        icon: 2
                    }, function (index) {
                        layer.closeAll();
                    });
                }
            })
JS;
            }

        }
        return <<<JS
        
        $basicScript
            $('$e').on('select2:clear', function (e) {
                clearResult();
            });
            $('$e').on('select2:select', function (e) {
              console.log(e.params.data.text);
              $('#myModal').remove()
              
              var url = '';
              var column = [];
              var callback;
              if(e.params.data.text == '指定商品'){
                  {$this->buildGoodsHtml()}
                  url = '/admin/api/goods';
                  column = [
                    {
                        checkbox: true,
                    }, {
                        title: 'id',
                        field: 'id',
                    }, {
                        title: '商品名称',
                        field: 'name',
                    }, {
                        title: '售价',
                        field: 'price',
                    }, {
                        title: '创建时间',
                        field: 'created_at',
                    }];
                   callback = function(result){
                        clearResult();
                        var result = $("#mytable").bootstrapTable('getSelections');
                        renderGoodsResult(result);
                        $('#myModal').modal('hide');
                   }
                   
                   basic(url,column,callback);
                  
              }else if(e.params.data.text == '指定分类'){
                  {$this->buildGoodsCatHtml()}
                  
                  url = '/admin/api/goodsCat';
                  column = [
                    {
                        checkbox: true,
                    }, {
                        title: 'id',
                        field: 'id',
                    }, {
                        title: '分类名称',
                        field: 'title',
                    }, {
                        title: '创建时间',
                        field: 'created_at',
                    }];
                   callback = function(result){
                        clearResult();
                        var result = $("#mytable").bootstrapTable('getSelections');
                        renderGoodsCatResult(result);
                        $('#myModal').modal('hide');
                   }
                   
                   basic(url,column,callback);
              }
            });
JS;
    }
}