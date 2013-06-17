<?php
Nos\I18n::current_dictionary('noviusos_blognews::common');
?>
<p>
    <label>
        <input type="radio" name="perm[<?= $application ?>::post][]" value="2_full_access" <?= (int) $role->getPermissionValue($application.'::post', 2) == 2 ? 'checked' : '' ?> />
        <?= __('Can add, edit, delete and publish posts') ?>
    </label>
</p>

<p>
    <label>
        <input type="radio" name="perm[<?= $application ?>::post][]" value="1_draft_only" <?= (int) $role->getPermissionValue($application.'::post', 2) == 1 ? 'checked' : '' ?> />
        <?= __('Can add, edit and delete unpublished posts only') ?>
    </label>
</p>


<p>
    <label>
        <input class="valueUnchecked" type="checkbox" name="perm[<?= $application ?>::category][]" value="yes" value-unchecked="no" <?= $role->getPermissionValue($application.'::category', 'yes') == 'yes' ? 'checked' : '' ?> />
        <?= __('Can add, edit and delete categories') ?>
    </label>
</p>
