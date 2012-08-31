<?php if ($app_config['summary']['enabled'] && $app_config['summary']['show']) { ?>
    <div class="summary">
        <?= nl2br($item->post_summary) ?>
    </div>
<?php }
