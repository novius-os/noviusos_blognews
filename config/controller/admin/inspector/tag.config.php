<?php
return array(
    'model' => '{{namespace}}\Model_Tag',
    'query' => array(
        'order_by' => 'tag_label',
    ),
    'appdesk' => array(
        'label' => __('Tags'),
    ),
    'input' => array(
        'key'   => 'tags.tag_id',
    ),
);
