<?php
use Goteo\Library\Page,
    Goteo\Library\Feed,
    Goteo\Library\Text;

$items = $this['items'];

?>
<div class="widget feed">
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.scroll-pane').jScrollPane({showArrows: true});
    });
    </script>
    <h3 class="title"><?php echo Text::get('feed-header'); ?></h3>

    <div style="height:auto;overflow:auto;margin-left:15px">

        <div class="block goteo">
           <h4><?php echo Text::get('feed-head-goteo'); ?></h4>
           <div class="item scroll-pane" style="height:800px;">
               <?php foreach ($items['goteo'] as $item) : 
                   echo Feed::subItem($item);
                endforeach; ?>
           </div>
        </div>

        <div class="block projects">
            <h4><?php echo Text::get('feed-head-projects'); ?></h4>
            <div class="item scroll-pane" style="height:800px;">
               <?php foreach ($items['projects'] as $item) :
                   echo Feed::subItem($item);
                endforeach; ?>
           </div>
        </div>
        <div class="block community last">
            <h4><?php echo Text::get('feed-head-community'); ?></h4>
            <div class="item scroll-pane" style="height:800px;">
               <?php foreach ($items['community'] as $item) :
                   echo Feed::subItem($item);
                endforeach; ?>
           </div>
        </div>
    </div>
</div>
