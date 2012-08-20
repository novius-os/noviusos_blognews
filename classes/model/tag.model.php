<?php
namespace Nos\BlogNews;

class Model_Tag extends \Nos\Orm\Model {
    protected static $_table_name = 'nos_tag';
    protected static $_primary_key = array('tag_id');

    protected static $_many_many = array();

    protected static $_behaviours = array(
        'Nos\Orm_Behaviour_Url' => array(
            'enhancers' => array(),
        ),
    );

    public static function _init() {
        static::$_behaviours['Nos\Orm_Behaviour_Sharable'] = array(
            'data' => array(
                \Nos\DataCatcher::TYPE_TITLE => array(
                    'value' => 'tag_label',
                    'useTitle' => __('Title'),
                ),
                \Nos\DataCatcher::TYPE_URL => array(
                    'value' => function($tag) {
                        return $tag->url_canonical();
                    },
                    'options' => function($tag) {
                        $urls = array();
                        foreach ($tag->urls() as $possible)
                        {
                            $urls[$possible['page_id'].'::'.$possible['itemUrl']] = $possible['url'];
                        }
                        return $urls;
                    },
                    'useTitle' => __('Url'),
                ),
            ),
            'data_catchers' => array(
                'blog_posts_rss_channel' => array(
                    'data_catcher' => 'rss_channel',
                    'title' => __('RSS Post channel'),
                ),
            ),
        );
    }

    public static function relations($specific = false)
    {
        $class = get_called_class();
        $post_class = \Inflector::get_namespace($class).'Model_Post';
        list($post_pk) = $post_class::primary_key();

        // @todo: should be loaded on config somewhere maybe
        $table_prefix_pos = strrpos(static::$_table_name, '_');
        $table_prefix = substr(static::$_table_name, 0, $table_prefix_pos);

        static::$_many_many['posts'] = array(
            'table_through' => $table_prefix.'_tag_post',
            'key_from' => static::$_primary_key[0],
            'key_through_from' => static::$_primary_key[0],
            'key_through_to' => $post_pk,
            'key_to' => $post_pk,
            'cascade_save' => true,
            'cascade_delete' => false,
            'model_to'       => $post_class,
        );

        return parent::relations($specific);
    }
}
