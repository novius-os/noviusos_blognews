
<?php
    echo $pagination->create_links(function($page) use ($type, $item, $config) {
        if ($type == 'main') {
            return $config['classes']['post']::get_list_url(array('page' => $page));
        } else {
            return $item->get_url(array('page' => $page));
        }
    });
?>
