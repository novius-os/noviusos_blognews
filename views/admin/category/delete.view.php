<div id="<?= $uniqid = uniqid('id_') ?>" class="fieldset standalone">
    <?php
    \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));

    echo '<p>', __('Please confirm the suppression below.'), '</p>';
    /*
    if ($posts_count == 0) {
        ?>
        <p><?= __('The category is empty and can be safely deleted.') ?></p>
        <p><?= __('Please confirm the suppression below.') ?></p>
        <?php
    } else {
        ?>
        <p><?= strtr(__(
                $posts_count == 1 ? 'There is <strong>one post</strong> in this category.'
                                  : 'There are <strong>{count} posts</strong> in this category.'
        ), array(
            '{count}' => $posts_count,
        )) ?></p>
        <p><?= __('To confirm the deletion, you need to enter this number in the field below') ?></p>
        <p><?= strtr(__('Yes, I want to delete all {count} posts'), array(
            '{count}' => '<input data-id="verification" data-verification="'.$posts_count.'" size="'.(mb_strlen($posts_count) + 1).'" />',
        )); ?></p>
        <?php
    }
    */
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
                /*if ($verification.length && $verification.val() != $verification.data('verification')) {
                    $.nosNotify(<?= \Format::forge()->to_json(__('Wrong confirmation')); ?>, 'error');

                    return;
                }*/
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
