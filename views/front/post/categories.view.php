<?php if ($app_config['categories']['enabled'] && $app_config['categories']['show']) { ?>
    <div class="categories">
    <?php
        if (count($item->categories) > 0) {
            $categories = array();
            foreach ($item->categories as $category) {
                $categories[$category->url()] = $category->cat_title;
            }
            $categories_str = implode(', ', array_map(function($href, $title) {
                return '<a href="'.$href.'">'.$title.'</a>';
            }, array_keys($categories), array_values($categories)));
            echo Str::tr(__('Categories: :categories'), array('categories' => $categories_str));
        }
    ?>
    </div>
<?php }
