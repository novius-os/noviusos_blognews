<?php
if ($app_config['comments']['enabled'] && $app_config['comments']['show_nb']) {
    ?>
    <div class="blognews_nb_comments">
    <?php
    if ($item->count_comments() > 0) {
        if ($item->count_comments() > 1) {
            echo Str::tr(e(__(':nb comments')), array('nb' => $item->count_comments()));
        } else {
            echo e(__('1 comment'));
        }
    } else {
        echo e(__('No comments'));
    }
    ?>
    </div>
    <?php
}
