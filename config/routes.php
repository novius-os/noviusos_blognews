<?php
return array(
    '{{application_name}}/front' => '{{application_name}}/front/list/1',
    '{{application_name}}/front/page/(:num)' => '{{application_name}}/front/list/$1',
    '{{application_name}}/front/(:segment)' => '{{application_name}}/front/item/$1',
    '{{application_name}}/front/rss/posts' => '{{application_name}}/front/rss_posts',
    '{{application_name}}/front/rss/posts/category/(:segment)' => '{{application_name}}/front/rss_posts_category/$1',
    '{{application_name}}/front/rss/posts/tag/(:segment)' => '{{application_name}}/front/rss_posts_tag/$1',
    '{{application_name}}/front/rss/posts/author/(:num)' => '{{application_name}}/front/rss_posts_author/$1',
    '{{application_name}}/front/rss/comments' => '{{application_name}}/front/rss_comments',
    '{{application_name}}/front/rss/comments/(:segment)' => '{{application_name}}/front/rss_comments_post/$1',
);