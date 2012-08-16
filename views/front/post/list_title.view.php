<?php
    $title = null;
    if ($type == 'tag') {
        $title = Str::tr(__('Tag: :tag'), array('tag' => $item->label));
        $link  = $item->get_url();
    }
    if ($type == 'category') {
        $title = Str::tr(__('Category: :category'), array('category' => $item->title));
        $link  = $item->get_url();
    }
    if ($title !== null) {
        echo '<h1><a href="'.$link.'">'.$title.'</a></h1>';
    }
?>