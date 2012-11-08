<?php
if ($app_config['summary']['enabled'] && $app_config['summary']['show']) {
    ?>
    <div class="blognews_summary">
        <?= nl2br(e($item->post_summary)) ?>
    </div>
    <?php
}
