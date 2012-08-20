<?php

    $title = null;

    if ($type == 'tag') {
        $title = Str::tr(__('Tag: :tag'), array('tag' => $object->tag_label));
        $link  = $object->url();
    }
    if ($type == 'category') {
        $title = Str::tr(__('Category: :category'), array('category' => $object->cat_title));
        $link  = $object->url();
    }
    if ($title !== null) {
        echo '<h1><a href="'.$link.'">'.$title.'</a></h1>';
    }
