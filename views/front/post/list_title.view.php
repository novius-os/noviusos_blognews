<?php
    $title = null;
    if ($type == 'tag') {
        $title = Str::tr(__('Tag: :tag'), array('tag' => $object->label));
        $link  = $object->get_url();
    }
    if ($type == 'category') {
        $title = Str::tr(__('Category: :category'), array('category' => $object->title));
        $link  = $object->get_url();
    }
    if ($title !== null) {
        echo '<h1><a href="'.$link.'">'.$title.'</a></h1>';
    }
?>