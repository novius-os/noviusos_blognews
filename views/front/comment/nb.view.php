<?php if ($app_config['comments']['enabled'] && $app_config['comments']['show_nb']) { ?>
<div class="nb_comments">
<?php
    if ($item->count_comments() > 0) {
        if ($item->count_comments() > 1) {
            echo Str::tr(__(':nb comments'), array('nb' => $item->count_comments()));
        } else {
            echo __('1 comment');
        }
    } else {
        echo __('No comments');
    }
?>
</div>
<?php }
