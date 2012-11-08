<div class="blognews_posts_list">
    <?= \View::forge('noviusos_blognews::front/post/list_title', array('type' => $type, 'item' => $item), false) ?>
    <div class="blognews_list">
<?php
foreach ($posts as $post) {
    echo \View::forge('noviusos_blognews::front/post/item', array('item' => $post), false);
}
?>
    </div>
<?php
if ($pagination) {
    echo \View::forge('noviusos_blognews::front/post/pagination', array('type' => $type, 'item' => $item, 'pagination' => $pagination), false);
}
?>
</div>
