<?php
Nos\I18n::current_dictionary('noviusos_blognews::common');
?>
<p>
    <label>
        <input type="radio" name="perm[<?= $application ?>::others_post][]" value="3_full_access" <?= (int) $role->getPermissionValue($application.'::others_post', 3) == 3 ? 'checked' : '' ?> />
        <?php
        // Other authors' posts
        echo __('Can edit and delete');
        ?>
    </label>
</p>

<p>
    <label>
        <input type="radio" name="perm[<?= $application ?>::others_post][]" value="2_visualise_only" <?= (int) $role->getPermissionValue($application.'::others_post') == 2 ? 'checked' : '' ?> />
        <?php
        // Other authors' posts
        echo __('Can visualise only');
        ?>
    </label>
</p>

<p>
    <label>
        <input type="radio" name="perm[<?= $application ?>::others_post][]" value="1_no_access" <?= (int) $role->getPermissionValue($application.'::others_post') == 1 ? 'checked' : '' ?> />
        <?php
        // Other authors' posts
        echo __('Can access her/his own posts only');
        ?>
    </label>
</p>
