<?php

echo $pagination->create_links(function($page) use ($type, $item) {

    if ($type == 'main') {
        $url = \Nos\Nos::main_controller()->getPageUrl();
        return $page == 1 ? $url : str_replace('.html', '/page/'.$page.'.html', $url);
    } else {
        return $item->url(array('page' => $page));
    }
});
