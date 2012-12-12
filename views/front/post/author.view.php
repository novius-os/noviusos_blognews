<?php
if ($app_config['authors']['enabled'] && $app_config['authors']['show']) {
    ?>
    <div class="blognews_author">
        <?= e(Str::tr(__('Author: :author'), array('author' => !empty($item->author) ? $item->author->fullname() : $item->post_author_alias))) ?>
    </div>
    <?php
}
