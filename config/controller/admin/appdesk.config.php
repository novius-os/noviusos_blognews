<?php
use Nos\I18n;
return array(
    'query' => array(
        'model' => 'Nos\BlogNews\Model_Post',
        'order_by' => array('post_created_at' => 'DESC'),
        'limit' => 20,
    ),
    'search_text' => 'post_title',
    'selectedView' => 'default',
    'views' => array(
        'default' => array(
            'name' => __('Default view'),
            'json' => array('static/apps/_blognews/js/admin/blognews.js', ''),
        ),
    ),
    'i18n' => array(
    ),
    'dataset' => array(
        'id'            => 'post_id',
        'title'         => 'post_title',
        'author' => array(
            'search_relation' => 'author',
            'search_column'   => 'author.user_name',
            'value' =>  function($item) {
                return $item->author->fullname();
            },
        ),
        'post_created_at' => array(
            'search_column'    =>  'post_created_at',
            'dataType'         => 'datetime',
            'value'            => function($item) {
                return \Date::create_from_string($item->post_created_at, 'mysql')->format('%m/%d/%Y %H:%M:%S'); //%m/%d/%Y %H:%i:%s
            },
        ),
        'url' => array(
            'value' => function($item) {
                return $item->url_canonical(array('preview' => true));
            },
        ),
        'actions' => array(
            'visualise' => function($item) {
                $url = $item->url_canonical(array('preview' => true));
                return !empty($url);
            }
        ),
    ),
    'inputs' => array(
        'startdate' => function($value, $query) {
            list($begin, $end) = explode('|', $value.'|');
            if ($begin) {
                if ($begin = Date::create_from_string($begin, '%Y-%m-%d')) {
                    $query->where(array('evt_date_begin', '>=', $begin->format('mysql')));
                }
            }
            if ($end) {
                if ($end = Date::create_from_string($end, '%Y-%m-%d')) {
                    $query->where(array('evt_date_begin', '<=', $end->format('mysql')));
                }
            }
            return $query;
        },
		'tag_id' => function($value, $query) {

			if ( is_array($value) && count($value) && $value[0]) {
				$query->related('tags', array(
					'where' => array(
						array('tags.tag_id', 'in', $value),
					),
				));
			}
			return $query;
		},
        'cat_id' => function($value, $query) {
			if ( is_array($value) && count($value) && $value[0]) {
				$query->related('categories', array(
					'where' => array(
						array('categories.cat_id', 'in', $value),
					),
				));
			}
			return $query;
		},
        'author_id' => function($value, $query) {
			if ( is_array($value) && count($value) && $value[0]) {
				$query->where(array('post_author_id', 'in', $value));
			}
			return $query;
		},
		'post_created_at' => function($value, $query) {
			list($begin, $end) = explode('|', $value.'|');
			if ($begin) {
				if ($begin = Date::create_from_string($begin, '%Y-%m-%d')) {
					$query->where(array('post_created_at', '>=', $begin->format('mysql')));
				}
			}
			if ($end) {
				if ($end = Date::create_from_string($end, '%Y-%m-%d')) {
					$query->where(array('post_created_at', '<=', $end->format('mysql')));
				}
			}
			return $query;
		},
    ),
    'appdesk' => array(
        'tab' => array(
            'label' => __('Posts'),
            'iconUrl' => 'static/apps/{{blognews.dir}}/img/{{blognews.icon_name}}-32.png'
        ),
        'actions' => array(
            'edit' => array(
                'name' => 'edit',
                'primary' => true,
                'icon' => 'pencil',
                'label' => __('Edit'),
                'action' => array(
                    'action' => 'nosTabs',
                    'tab' => array(
                        'url' => 'admin/{{blognews.dir}}/post/insert_update/{{id}}',
                        'label' => __('Edit this post'),
                        'iconUrl' => 'static/apps/{{blognews.dir}}/img/{{blognews.icon_name}}-16.png'
                    ),
                ),
            ),
            'delete' => array(
                'name' => 'delete',
                'primary' => true,
                'icon' => 'trash',
                'label' => __('Delete'),
                'action' => array(
                    'action' => 'confirmationDialog',
                    'dialog' => array(
                        'contentUrl' => 'admin/{{blognews.dir}}/post/delete/{{id}}',
                        'title' => __('Delete this post')
                    ),
                ),
            ),
            'visualise' => array(
                'label' => 'Visualise',
                'name' => 'visualise',
                'primary' => true,
                'iconClasses' => 'nos-icon16 nos-icon16-eye',
                'action' => array(
                    'action' => 'window.open',
                    'url' => '{{url}}?_preview=1'
                ),
            ),
        ),

        // Event name for reloading the grid
        'reloadEvent' => '{{blognews.namespace}}\\Model_Post',
        'appdesk' => array(
            'texts' => array(
                'items' => __("posts"),
                'item' => __("post")
            ),
            'adds' => array(
                'post' => array(
                    'label' => __('Add a post'),
                    'action' => array(
                        'action' => 'nosTabs',
                        'method' => 'add',
                        'tab' => array(
                            'url' => 'admin/{{blognews.dir}}/post/insert_update?lang={{lang}}',
                            'label' => __('Add a post'),
                            'iconUrl' => 'static/apps/{{blognews.dir}}/img/{{blognews.icon_name}}-16.png'
                        ),
                    ),
                ),
                'category' => array(
                    'label' => __('Add a category'),
                    'action' => array(
                        'action' => 'nosTabs',
                        'method' => 'add',
                        'tab' => array(
                            'url' => 'admin/{{blognews.dir}}/category/insert_update?lang={{lang}}',
                            'label' => __('Add a category'),
                            'iconUrl' => 'static/apps/{{blognews.dir}}/img/{{blognews.icon_name}}-16.png'
                        ),
                    ),
                ),
            ),

            // Largeur de la colonne des inspecteurs de gauche en px
            'splittersVertical' => 250,
            'grid' => array(
                'proxyUrl' => 'admin/{{blognews.dir}}/appdesk/json',

                /**
                 * Liste des colonnes du affich�es dans la grid. Les cl�s sont celles du dataset d�finies dans le fichier de config PHP
                 */
                'columns' => array(
                    'title' => array(
                        'headerText' => __('Title'),
                        'dataKey' => 'title'
                    ),
                    'lang' => array(
                        'lang' => true
                    ),
                    'author' => array(
                        'headerText' => __('Author'),
                        'dataKey' => 'author'
                    ),
                    'published' => array(
                        'headerText' => __('Status'),
                        'dataKey' => 'publication_status'
                    ),
                    'post_created_at' => array(
                        'headerText' => __('Date'),
                        'dataKey' => 'post_created_at',
                        'dataFormatString' => 'MM/dd/yyyy HH:mm:ss',
                        'showFilter' => false,
                        'sortDirection' => 'descending'
                    ),
                    'actions' => array(
                        'actions' => array('edit', 'delete', 'visualise'),
                    ),
                ),
            ),

            /**
             * Liste des inspecteurs autour de la grid
             */
            'inspectors' => array(
                'startdate' => array(
                    'vertical' => true,
                    'label' => __('Created date'),
                    'url' => 'admin/noviusos_blognews/inspector/date/list',
                    'inputName' => 'startdate'
                ),
                'categories' => array(
                    'vertical' => true,
                    'reloadEvent' => '{{blognews.namespace}}\\Model_Category',
                    'url' => 'admin/{{blognews.dir}}/inspector/category/list',
                    'inputName' => 'cat_id[]',
                    'label' => __('Categories'),
                    'treeGrid' => array(
                        'treeUrl' => 'admin/{{blognews.dir}}/inspector/category/json',
                        'sortable' => true,
                        'columns' => array(
                            'title' => array(
                                'headerText' => __('Categories'),
                                'dataKey' => 'title'
                            ),
                            'actions' => array(
                                'actions' => array(
                                    array(
                                        'name' => 'edit',
                                        'primary' => true,
                                        'label' => __('Edit this category'),
                                        'icon' => 'pencil',
                                        'action' => array(
                                            'action' => 'nosTabs',
                                            'tab' => array(
                                                'url' => 'admin/{{blognews.dir}}/category/insert_update/{{id}}',
                                                'label' => 'Edit the "{{title}}" folder'
                                            ),
                                        ),
                                    ),
                                    array(
                                        'name' => 'delete',
                                        'label' => __('Delete this category'),
                                        'icon' => 'trash',
                                        'action' => array(
                                            'action' => 'confirmationDialog',
                                            'dialog' => array(
                                                'contentUrl' => 'admin/{{blognews.dir}}/category/delete/{{id}}',
                                                'title' => __('Delete this category'),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ), // ~treeGrid
                ),
                'tags' => array(
                    'reloadEvent' => '{{blognews.namespace}}\\Model_Tag',
                    'label' => __('Tags'),
                    'url' => 'admin/{{blognews.dir}}/inspector/tag/list',
                    'grid' => array(
                        'urlJson' => 'admin/{{blognews.dir}}/inspector/tag/json',
                        'columns' => array(
                            'title' => array(
                                'headerText' => __('Tag'),
                                'dataKey' => 'title'
                            ),
                            'actions' => array(
                                'actions' => array(
                                    array(
                                        'name' => 'delete',
                                        'action' => array(
                                            'action' => 'confirmationDialog',
                                            'dialog' => array(
                                                'contentUrl' => 'admin/{{blognews.dir}}/tag/delete/{{id}}',
                                                'title' => __('Delete a tag')
                                            ),
                                        ),
                                        'label' => __('Delete'),
                                        'primary' => true,
                                        'icon' => 'trash'
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'inputName' => 'tag_id[]',
                    'vertical' => true
                ),
                'authors' => array(
                    'reloadEvent' => '{{blognews.namespace}}\\Model_User',
                    'label' => __('Authors'),
                    'url' => 'admin/{{blognews.dir}}/inspector/author/list',
                    'grid' => array(
                        'columns' => array(
                            'title' => array(
                                'headerText' => __('Author'),
                                'dataKey' => 'title'
                            ),
                        ),
                        'urlJson' => 'admin/{{blognews.dir}}/inspector/author/json'
                    ),
                    'inputName' => 'author_id[]',
                    'vertical' => true
                ),
            ), // ~inspectors
        ),
    ),
);
