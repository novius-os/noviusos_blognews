<?php if ($app_config['publication_date']['enabled'] && $app_config['publication_date']['show']) { ?>
    <div class="date">
        <?= Date::forge(strtotime($item->post_created_at))->format($app_config['publication_date']['front']['format']); ?>
    </div>
<?php } ?>