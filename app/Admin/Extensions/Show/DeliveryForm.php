<?php
/**
 * Created by PhpStorm.
 * User: zhouminjie
 * Date: 2020-05-08
 * Time: 17:06
 */

namespace App\Admin\Extensions\Show;

use Encore\Admin\Show\AbstractField;

class DeliveryForm extends AbstractField
{
    public function render($orderId = '')
    {
        if (!$orderId) return '';
        return ' <form class="deliveryForm">
                        <input type="hidden" class="form-control" name="order_id" value="' . $orderId . '">
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">快递公司</label>
                            <div class="col-sm-4">
                                <select class="box box-solid box-default no-margin box-show select2" name="delivery_name">
                                    <option value="顺丰快递">顺丰快递</option>
                                    <option value="韵达快递">韵达快递</option>
                                    <option value="中通快递">中通快递</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">快递单号</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="delivery_no">
                            </div>
                        </div>
                        
                        <div class="form-group ">
                         <label class="col-sm-2 control-label">&nbsp;</label>
                            <a class="btn btn-primary" style="margin-left: 15px;" id="delivery"> 去发货 </a>
                        </div>
                    </form>
                    <script>
                        $(document).ready(function(){
                            $(".select2").select2();
                        })
                        $("#delivery").click(function() {
                            $.ajax({
                                url : "/api/admin/store/order/delivery",
                                method : "post",
                                data : $(".deliveryForm").serialize(),
                                success : function($data) {
                                    alert("发货成功");
                                    window.location.reload();
                                },
                                error : function(error) {
                                    alert("错误，" + error.responseJSON.message)
                                    return;
                                }
                            })
                           
                        })
                    </script>';
    }
}