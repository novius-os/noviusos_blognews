<div id="<?= $uniqid = uniqid('id_') ?>" class="fieldset standalone">
    <?php
    \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));

    echo '<p>', __('Please confirm the suppression below.'), '</p>';
    ?>
    <p>
        <button class="primary ui-state-error" data-icon="trash" data-id="confirmation"><?= __('Confirm the deletion') ?></button>
        &nbsp; <?= __('or') ?> &nbsp;
        <a href="#" data-id="cancel"><?= __('Cancel') ?></a>
    </p>
</div>

<script type="text/javascript">
require(
    ['jquery-nos'],
    function($) {
        $(function() {
            var $container    = $('#<?= $uniqid ?>').nosFormUI();
            var $verification = $container.find('input[data-id=verification]');
            var $confirmation = $container.find('button[data-id=confirmation]');

            $confirmation.click(function(e) {
                e.preventDefault();
                $container.nosAjax({
                    url : 'admin/<?php echo $app_name; ?>/category/delete_confirm',
                    method : 'POST',
                    data : {
                        id : <?= $category->id ?>
                    },
                    success : function(json) {
                        $container.nosDialog('close');
                    }
                });
            });

            $container.find('a[data-id=cancel]').click(function(e) {
                e.preventDefault();
                $container.nosDialog('close');
            });

        });
    });
</script>
