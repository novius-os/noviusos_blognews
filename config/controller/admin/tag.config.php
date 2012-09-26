<?php
return array (
    'controller_url'  => 'admin/noviusos_blognews/tag',
    'model' => 'Nos\\BlogNews\\Model_Tag',
    'messages' => array(
        'successfully deleted' => __('The tag has successfully been deleted!'),
        'you are about to delete, confim' => __('You are about to delete the tag <span style="font-weight: bold;">":title"</span>. Are you sure you want to continue?'),
        'you are about to delete' => __('You are about to delete the tag <span style="font-weight: bold;">":title"</span>.'),
        'exists in multiple site' => __('This tag exists in <strong>{count} sites</strong>.'),
        'delete in the following sites' => __('Delete this tag in the following sites:'),
        'item deleted' => __('This tag has been deleted.'),
        'not found' => __('Tag not found'),
    ),
);
