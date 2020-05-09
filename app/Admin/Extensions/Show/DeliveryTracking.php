<?php
/**
 * Created by PhpStorm.
 * User: zhouminjie
 * Date: 2020-05-08
 * Time: 17:06
 */

namespace App\Admin\Extensions\Show;

use Encore\Admin\Show\AbstractField;

class DeliveryTracking extends AbstractField
{
    public function render($arg = '')
    {
        if (!$arg) return '';
        // 返回任意可被渲染的内容
        $content = "";
        foreach ($arg['result']['list'] as $item) {
            $date = explode(' ', $item['time']);
            $content .= <<<EOF
                                         <!-- timeline time label -->
                                        <li class="time-label">
                                            <span class="bg-red">
                                                {$date[0]}
                                            </span>
                                        </li>
                                        <!-- /.timeline-label -->
                                    
                                        <!-- timeline item -->
                                        <li>
                                            <!-- timeline icon -->
                                            <i class="fa fa-envelope bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> {$date[1]}</span>
                                    
                                                <!--<h3 class="timeline-header"><a href="#">Support Team</a> ...</h3>-->
                                    
                                                <div class="timeline-body">
                                                    {$item['status']}
                                                </div>
                                    
                                                <!--<div class="timeline-footer">-->
                                                    <!--<a class="btn btn-primary btn-xs">...</a>-->
                                                <!--</div>-->
                                            </div>
                                        </li>
                                        <!-- END timeline item -->
EOF;
        }

        return '<ul class="timeline">
                                        <!-- timeline time label -->
                                       ' . $content . ' 
                                    </ul>';
    }
}