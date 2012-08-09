
<?php
    echo $pagination->create_links(function($page) use ($type, $object, $config) {
        if ($type == 'main') {
            return $config['classes']['post']::get_list_url(array('page' => $page));
        } else {
            return $object->get_url(array('page' => $page));
        }
    });
?>
