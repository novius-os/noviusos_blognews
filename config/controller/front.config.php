<?php
return array(
    'date_format'   => '%A %e %B %Y',
    'link_on_title' => false,
    'item_per_page' => 10,
    'order_by'    => array('post_created_at' => 'DESC', 'post_id' => 'DESC'),
    'views' => array(
        'list' => 'noviusdev_blognews::front/post/list',
        'item' => 'noviusdev_blognews::front/post/show'
    ),

);
?>
