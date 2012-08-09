<?php
use Nos\I18n;
return array(
    'query' => array(
        'model' => 'NoviusDev\BlogNews\Model_Post',
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
        'Title'     => __('Title'),
    ),
    'dataset' => array(
        'id'            => 'post_id',
        'title'         => 'post_title',
        'author' => array(
            'search_relation' => 'author',
            'search_column'   => 'author.user_name',
            'value' =>  function($object) {
                return $object->author->fullname();
            },
        ),
        'post_created_at' => array(
            'search_column'    =>  'post_created_at',
            'dataType'         => 'datetime',
            'value'            => function($object) {
                return \Date::create_from_string($object->created_at, 'mysql')->format('%m/%d/%Y %H:%M:%S'); //%m/%d/%Y %H:%i:%s
            },
        ),
        'url' => array(
            'value' => function($object) {
                return $object->first_url();
            },
        ),
        'actions' => array(
            'visualise' => function($object) {
                $url = $object->first_url();
                return !empty($url);
            }
        ),

//        'date' => array(
//            'search_column'    => 'evt_date_begin',
//            'dataType'         => 'datetime',
//            'value'            => function($object) {
//                return \Date::create_from_string($object->evt_date_begin, 'mysql')->format('%m/%d/%Y %H:%M');
//            },
//        ),
//        'actions' => array(
//            'delete' => function($voiture) {
//                return $voiture->voit_modele != '306';
//            }
//        ),
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
		'blog_created_at' => function($value, $query) {
			list($begin, $end) = explode('|', $value.'|');
			if ($begin) {
				if ($begin = Date::create_from_string($begin, '%Y-%m-%d')) {
					$query->where(array('blog_created_at', '>=', $begin->format('mysql')));
				}
			}
			if ($end) {
				if ($end = Date::create_from_string($end, '%Y-%m-%d')) {
					$query->where(array('blog_created_at', '<=', $end->format('mysql')));
				}
			}
			return $query;
		},
    ),

);
