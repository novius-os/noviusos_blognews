<?php
if ($app_config['tags']['enabled'] && $app_config['tags']['show']) {
    ?>
    <div class="tags">
    <?php
    if (count($item->tags) > 0) {
        $tags = array();
        foreach ($item->tags as $tag) {
            $tags[$tag->url()] = $tag->tag_label;
        }
        $tags_str = implode(', ', array_map(function($href, $title) {
            return '<a href="'.$href.'">'.$title.'</a>';
        }, array_keys($tags), array_values($tags)));
        echo Str::tr(__('Tags: :tags'), array('tags' => $tags_str));
    }
    ?>
    </div>
    <?php
}
