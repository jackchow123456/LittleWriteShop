<?php
/**
 * Created by PhpStorm.
 * User: zhouminjie
 * Date: 2020-05-08
 * Time: 17:06
 */

namespace App\Admin\Extensions\Show;

use Dcat\Admin\Show\AbstractField;

class DeliveryTracking extends AbstractField
{
    public function render($arg = '')
    {
        if (!$arg) return '';

        $icon = collect(['icon-activity', 'icon-airplay', 'icon-alert-triangle', 'icon-alert-circle', 'icon-arrow-down-right']);
        $bg = collect(['bg-blue', 'bg-red', 'bg-info', 'bg-gray', 'bg-green', 'bg-danger', 'bg-success']);

        // 返回任意可被渲染的内容
        $content = "";
        foreach ($arg['result']['list'] as $item) {
            $date = explode(' ', $item['time']);
            $theIcon = $icon->random();
            $theBg = $bg->random();
            $content .= <<<EOF

<!-- Main node for this component -->
<div class="timeline">
  <!-- Timeline time label -->
  <div class="time-label">
    <span class="bg-green">{$date[0]}</span>
  </div>
  <div>
  <!-- Before each timeline item corresponds to one icon on the left scale -->
    <i class="fas feather {$theIcon} {$theBg}"></i>
    <!-- Timeline item -->
    <div class="timeline-item">
    <!-- Time -->
      <span class="time"><i class="fas fa-clock"></i> {$date[1]}</span>
      <!-- Header. Optional -->
      <!--<h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>-->
      <!-- Body -->
      <div class="timeline-body">
        {$item['status']}
      </div>
      <!-- Placement of additional controls. Optional -->
      <!--<div class="timeline-footer">-->
        <!--<a class="btn btn-primary btn-sm">Read more</a>-->
        <!--<a class="btn btn-danger btn-sm">Delete</a>-->
      <!--</div>-->
    </div>
  </div>
  <!-- The last icon means the story is complete -->
  <!--<div>-->
    <!--<i class="fas fa-clock bg-gray"></i>-->
  <!--</div>-->
</div>
EOF;
        }

        return '<ul class="timeline">
                                        <!-- timeline time label -->
                                       ' . $content . ' 
                                    </ul>';
    }
}