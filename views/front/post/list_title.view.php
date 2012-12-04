<?php
$title = null;

\Nos\I18n::current_dictionary(array('noviusos_blognews::common'));

if ($type == 'tag') {
    $title = Str::tr(__('Tag: :tag'), array('tag' => $item->tag_label));
    $link  = $item->url();
}
if ($type == 'category') {
    $title = Str::tr(__('Category: :category'), array('category' => $item->cat_title));
    $link  = $item->url();
}
if ($title !== null) {
    echo '<h1><a href="'.$link.'">'.e($title).'</a></h1>';
}
