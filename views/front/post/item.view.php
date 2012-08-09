<div class="post_item">
    <div class="primary_information">
        <?= \View::forge('noviusdev_blognews::front/post/title', array('item' => $item)) ?>
        <?= \View::forge('noviusdev_blognews::front/post/summary', array('item' => $item)) ?>
    </div>
    <div class="secondary_information">
        <?= \View::forge('noviusdev_blognews::front/post/author', array('item' => $item)) ?>
        <?= \View::forge('noviusdev_blognews::front/post/publication_date', array('item' => $item)) ?>
        <?= \View::forge('noviusdev_blognews::front/post/categories', array('item' => $item)) ?>
        <?= \View::forge('noviusdev_blognews::front/post/tags', array('item' => $item)) ?>
        <?= \View::forge('noviusdev_blognews::front/comment/nb', array('item' => $item)) ?>
    </div>
</div>