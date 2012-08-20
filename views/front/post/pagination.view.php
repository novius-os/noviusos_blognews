<?php

echo $pagination->create_links(function($page) use ($type, $object, $config) {

    if ($type == 'main') {
        return \Nos\Nos::main_controller()->getEnhancedUrlPath().($page == 1) ? '' : 'page/'.$page.'.html';
    } else {
        return $object->url(array('page' => $page));
    }
});

