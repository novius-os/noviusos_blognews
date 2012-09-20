<?php
if (isset($item->medias->thumbnail)) {
    ?>
<img src="<?= $item->medias->thumbnail->get_public_path_resized(200) ?>" class="blognews_thumbnail" />
    <?php
}
