<div class="blognews_post blognews_post_show">
    <?= \View::forge('noviusos_blognews::front/post/thumbnail', array('item' => $item)) ?>
    <?= \View::forge('noviusos_blognews::front/post/title', array('item' => $item, 'context' => 'show')) ?>
    <?= \View::forge('noviusos_blognews::front/post/publication_date', array('item' => $item)) ?>

    <?= \View::forge('noviusos_blognews::front/post/summary', array('item' => $item)) ?>
    <?= \View::forge('noviusos_blognews::front/post/content', array('item' => $item)) ?>

    <?= \View::forge('noviusos_blognews::front/post/tags', array('item' => $item)) ?>
    <?= \View::forge('noviusos_blognews::front/post/categories', array('item' => $item)) ?>
<?php if ($app_config['comments']['enabled']) { ?>
    <div class="blognews_comments" id="comments">
        <?= \View::forge('noviusos_blognews::front/comment/nb', array('item' => $item)) ?>
        <?php if ($app_config['comments']['show']) { ?>
            <?= \View::forge('noviusos_comments::front/list', array('comments' => $item->comments), true) ?>
        <?php } ?>
        <?php if ($app_config['comments']['can_post']) { ?>
            <?= \View::forge('noviusos_comments::front/form', array('add_comment_success' => $add_comment_success, 'use_recaptcha' => $app_config['comments']['use_recaptcha']), true) ?>
        <?php } ?>
    </div>
<?php } ?>
</div>
