<?php
return array(
    'data' => array(
        'fields' => array(
            'post_title' => array(
                'label'    => __('Title')
            ),
            'author->user_name' => array(
                'label'         => __('Author'),
                'value' => function ($item) {
                    return $item->author->fullname();
                },
            ),
            'post_created_at' => array(
                'label'    => __('Date'),
                'value' => function ($item) {
                    return \Date::create_from_string($item->post_created_at, 'mysql')->format('%m/%d/%Y %H:%M:%S'); //%m/%d/%Y %H:%i:%s
                },
                'appdesk' => array(
                    'dataType' => 'datetime',
                )
            ),
            'preview_url' => array(
                'value' => function($item) {
                    return $item->preview_url();
                },
                'visible' => false
            ),
        ),
        // Order is important
        'contexts' => array(
            'appdesk' => array('post_title', 'context', 'author->user_name', 'publication_status', 'post_created_at', 'preview_url')
        )
    )
);
/*
return array(
    'fields' => array(
        'title' => array(
            'column' => 'post_title',
            'label'    => __('Title'),
            'edit' => array(
                'form' => array(
                    'type' => 'text',
                ),
                'validation' => array(
                    'required',
                    'min_length' => array(2),
                )
            )
        ),
        'context' => true,
        'author' => array(
            'headerText'    => __('Author'),
            'search_relation' => 'author',
            'search_column' => 'author.user_name',
            'value' => function ($item) {
                return $item->author->fullname();
            },
        ),
        'publication_status' => true,
        'post_created_at' => array(
            'headerText'    => __('Date'),
            'search_column' => 'post_created_at',
            'dataType' => 'datetime',
            'value' => function ($item) {
                return \Date::create_from_string($item->post_created_at, 'mysql')->format('%m/%d/%Y %H:%M:%S'); //%m/%d/%Y %H:%i:%s
            },
        ),
        'preview_url' => array(
            'value' => function($item) {
                return $item->preview_url();
            },
            'visible' => false
        ),
    )
);
*/


/*
 *

array(
        'post_id' => array (
            'label' => 'ID: ',
            'form' => array(
                'type' => 'hidden',
            ),
            'dont_save' => true,
            // requis car la clé primaire ne correspond pas (le getter fait le taf mais
            // les mécanismes internes lèvent une exception)
        ),
        'post_title' => array(
            'label' => 'Titre',
            'form' => array(
                'type' => 'text',
            ),
            'validation' => array(
                'required',
                'min_length' => array(2),
            ),
        ),
        'post_summary' => array (
            'label' => __('Summary'),
            'template' => '<td class="row-field">{field}</td>',
            'form' => array(
                'type' => 'textarea',
                'rows' => '6',
            ),
        ),
        'post_author_alias' => array(
            'label' => __('Alias: '),
            'form' => array(
                'type' => 'text',
            ),
        ),
        'post_virtual_name' => array(
            'label' => __('URL: '),
            'widget' => 'Nos\Widget_Virtualname',
            'validation' => array(
                'required',
                'min_length' => array(2),
            ),
        ),
        'author->user_fullname' => array(
            'label' => __('Author: '),
            'widget' => 'Nos\Widget_Text',
            'editable' => false,
            'template' => '<p>{label} {field}</p>'
        ),
        'wysiwygs->content->wysiwyg_text' => array(
            'label' => __('Content'),
            'widget' => 'Nos\Widget_Wysiwyg',
            'template' => '{field}',
            'form' => array(
                'style' => 'width: 100%; height: 500px;',
            ),
        ),
        'medias->thumbnail->medil_media_id' => array(
            'label' => '',
            'widget' => 'Nos\Widget_Media',
            'form' => array(
                'title' => 'Thumbnail',
            ),
        ),
        'post_created_at' => array(
            'form' => array(
                'type' => 'text',
            ),
            'populate' =>
                function($item)
                {
                    if (\Input::method() == 'POST') {
                        return \Input::post('post_created_at_date').' '.\Input::post('post_created_at_time').':00';
                    }

                    return $item->post_created_at;
                }
        ),
        'post_created_at_date' => array(
            'label' => __('Created on:'),
            'widget' => 'Nos\Widget_Date_Picker',
            'template' => '<p>{label}<br/>{field}',
            'dont_save' => true,
            'populate' =>
                function($item)
                {
                    if ($item->post_created_at && $item->post_created_at!='0000-00-00 00:00:00') {
                        return \Date::create_from_string($item->post_created_at, 'mysql')->format('%Y-%m-%d');
                    } else {
                        return \Date::forge()->format('%Y-%m-%d');
                    }
                }
        ),
        'post_created_at_time' => array(
            'label' => __('Created time:'),
            'widget' => 'Nos\Widget_Time_Picker',
            'dont_save' => true,
            'template' => ' {field}</p>',
            'populate' =>
                function($item)
                {
                    if ($item->post_created_at && $item->post_created_at!='0000-00-00 00:00:00') {
                        return \Date::create_from_string($item->post_created_at, 'mysql')->format('%H:%M');
                    } else {
                        return \Date::forge()->format('%H:%M');
                    }
                }
        ),
        'post_read' => array(
            'label' => __('Read'),
            'template' => '<p>{label} {field} times</p>',
            'form' => array(
                'type' => 'text',
                'size' => '4',
            ),
        ),
        'tags' => array(
            'label' => __('Tags'),
            'widget' => 'Nos\Widget_Tag',
            'widget_options' => array(
                'model'         => '{{namespace}}\\Model_Tag',
                'label_column'  => 'tag_label',
                'relation_name' => 'tags'
            ),
        ),
        'categories' => array(
            'widget' => 'Nos\BlogNews\Widget_Category_Selector',
            'widget_options' => array(
                'width' => '250px',
                'height' => '250px',
                'namespace' => '{{namespace}}',
                'application_name' => '{{application_name}}',
                'multiple' => '1',
            ),
            'label' => __(''),
            'form' => array(
            ),
            //'dont_populate' => true,
            'before_save' =>
                function($item, $data)
                {
                    $item->categories;//fetch et 'cree' la relation
                    unset($item->categories);

                    $category_class = \Config::load_and_get('noviusos_blognews::config.namespace').'\\Model_Category';
                    if (!empty($data['categories'])) {
                        foreach ($data['categories'] as $cat_id) {
                            if (ctype_digit($cat_id) ) {
                                $item->categories[$cat_id] = $category_class::find($cat_id); // @todo: come back after...
                            }
                        }
                    }
                },
        ),
        'save' => array(
            'label' => '',
            'form' => array(
                'type' => 'submit',
                'tag' => 'button',
                'value' => __('Save'),
                'class' => 'primary',
                'data-icon' => 'check',
            ),
        ),
    )
 *
 *
 */