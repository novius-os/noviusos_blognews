<?php
$datas = array(
    'title' => 'title',
    //'id' => 'blog_id',
    'large' => true,
    'medias' => array('medias->thumbnail->medil_media_id'),//'medias->thumbnail->medil_media_id'),

    'save' => 'save',

    'subtitle' => array('summary'),

    'content' => array(
        View::forge('form/expander', array(
            'title'   => __('Content'),
            'nomargin' => true,
            'content' => '',
            'options' => array(
                'allowExpand' => false,
            ),
        ), false),
    ),

    'menu' => array(
        // user_fullname is not a real field in the database
        __('Meta') => array('field_template' => '{field}', 'fields' => array('author->user_fullname', 'author_alias', 'created_at_date', 'created_at_time', 'read')),
        __('URL (post address)') => array('virtual_name'),
        __('Tags') => array('tags'),
        __('Categories') => array('categories'),
    ),
);

if (!\Config::get('noviusdev_news::config.summary.enabled')){
    unset($datas['subtitle']);
}
if (!\Config::get('noviusdev_news::config.tags.enabled')){
    unset($datas['menu'][__('Tags')]);
}
if (!\Config::get('noviusdev_news::config.categories.enabled')){
    unset($datas['menu'][__('Categories')]);
}
if (!\Config::get('noviusdev_news::config.authors.enabled')){
    $datas['menu'][__('Meta')] = array('field_template' => '{field}', 'fields' => array('created_at_date', 'created_at_time', 'read'));
}
return $datas;