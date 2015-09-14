<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Nos\BlogNews;

class Model_Post extends \Nos\Orm\Model
{

    protected static $_primary_key = array('news_id');
    protected static $_table_name = '';

    protected static $_title_property = 'post_title';
    protected static $_properties = array(
        'post_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => false,
        ),
        'post_title' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
        ),
        'post_summary' => array(
            'default' => null,
            'data_type' => 'text',
            'null' => false,
        ),
        'post_author_alias' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'post_author_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'post_created_at' => array(
            'data_type' => 'timestamp',
            'null' => false,
        ),
        'post_updated_at' => array(
            'data_type' => 'timestamp',
            'null' => false,
        ),
        'post_context' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
        ),
        'post_context_common_id' => array(
            'default' => null,
            'data_type' => 'int',
            'null' => false,
        ),
        'post_context_is_main' => array(
            'default' => 0,
            'data_type' => 'tinyint',
            'null' => false,
        ),
        'post_published' => array(
            'default' => null,
            'data_type' => 'tinyint',
            'null' => false,
        ),
        'post_publication_start' => array(
            'default' => null,
            'data_type' => 'datetime',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'post_publication_end' => array(
            'default' => null,
            'data_type' => 'datetime',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
        'post_read' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => false,
        ),
        'post_virtual_name' => array(
            'default' => null,
            'data_type' => 'varchar',
            'null' => false,
            'character_maximum_length' => 100,
        ),
        'post_updated_by_id' => array(
            'default' => null,
            'data_type' => 'int unsigned',
            'null' => true,
            'convert_empty_to_null' => true,
        ),
    );

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'mysql_timestamp' => true,
            'property'=>'post_created_at'
        ),
        'Orm\Observer_UpdatedAt' => array(
            'mysql_timestamp' => true,
            'property'=>'post_updated_at'
        ),
    );

    protected static $_behaviours = array(
        'Nos\Orm_Behaviour_Publishable' => array(
            'publication_state_property' => 'post_published',
            'publication_start_property' => 'post_publication_start',
            'publication_end_property' => 'post_publication_end',
        ),
        'Nos\Orm_Behaviour_Urlenhancer' => array(
            'enhancers' => array(),
        ),
        'Nos\Orm_Behaviour_Virtualname' => array(
            'virtual_name_property' => 'post_virtual_name',
        ),
        'Nos\Orm_Behaviour_Twinnable' => array(
            'context_property'      => 'post_context',
            'common_id_property' => 'post_context_common_id',
            'is_main_property' => 'post_context_is_main',
        ),
        'Nos\Orm_Behaviour_Author' => array(
            'created_by_property' => 'post_author_id',
            'updated_by_property' => 'post_updated_by_id',
        ),
        'Nos\BlogNews\Orm_Behaviour_Cachemanager' => array(),
    );

    protected static $_has_one = array();
    protected static $_belongs_to  = array();
    protected static $_has_many  = array();
    protected static $_many_many = array();
    protected static $_twinnable_has_one = array();
    protected static $_twinnable_has_many = array();
    protected static $_twinnable_belongs_to = array();
    protected static $_twinnable_many_many = array();

    public static function _init()
    {
        $class = get_called_class();
        list($app) = \Config::configFile($class);
        \Config::load($app.'::config', true);
        $withCom = \Config::get($app.'::config.comments.enabled');

        \Nos\I18n::current_dictionary(array('noviusos_blognews::common'));
        static::$_behaviours['Nos\Orm_Behaviour_Sharable'] = array(
            'data' => array(
                \Nos\DataCatcher::TYPE_TITLE => array(
                    'value' => 'post_title',
                    'useTitle' => __('Use post title'),
                ),
                \Nos\DataCatcher::TYPE_URL => array(
                    'value' =>
                        function ($post) {
                            $urls = $post->urls();
                            if (empty($urls)) {
                                return null;
                            }
                            reset($urls);

                            return key($urls);
                        },
                    'options' =>
                        function ($post) {
                            return $post->urls();
                        },
                ),
                \Nos\DataCatcher::TYPE_IMAGE => array(
                    'value' =>
                        function ($post) {
                            $possible = $post->possible_medias();

                            return \Arr::get(array_keys($possible), 0, null);
                        },
                    'options' =>
                        function ($post) {
                            return $post->possible_medias();
                        },
                ),
                \Nos\DataCatcher::TYPE_TEXT => array(
                    'value' => 'post_summary',
                    'useTitle' => __('Use post summary'),
                ),
            ),
        );

        if ($withCom) {
            static::$_behaviours['Nos\Comments\Orm_Behaviour_Commentable'] = array();
        }
    }

    public static function relations($specific = false)
    {
        $class = get_called_class();
        $category_class = \Inflector::get_namespace($class).'Model_Category';
        list($category_pk) = $category_class::primary_key();
        $tag_class = \Inflector::get_namespace($class).'Model_Tag';
        list($tag_pk) = $tag_class::primary_key();

        // @todo: should be loaded on config somewhere maybe
        $table_prefix_pos = strrpos(static::$_table_name, '_');
        $table_prefix = substr(static::$_table_name, 0, $table_prefix_pos);

        static::$_many_many['categories'] = array(
            'table_through' => $table_prefix.'_category_post',
            'key_from' => static::$_primary_key[0],
            'key_through_from' => static::$_primary_key[0],
            'key_through_to' => $category_pk,
            'key_to' => $category_pk,
            'cascade_save' => true,
            'cascade_delete' => false,
            'model_to'       => $category_class,
        );

        static::$_many_many['tags'] = array(
            'table_through' => $table_prefix.'_tag_post',
            'key_from' => static::$_primary_key[0],
            'key_through_from' => static::$_primary_key[0],
            'key_through_to' => $tag_pk,
            'key_to' => $tag_pk,
            'cascade_save' => true,
            'cascade_delete' => false,
            'model_to'       => $tag_class,
        );

        static::$_belongs_to['author'] = array(
            'key_from' => static::prefix().'author_id',
            'model_to' => 'Nos\User\Model_User',
            'key_to' => 'user_id',
            'cascade_save' => false,
            'cascade_delete' => false,
        );

        list($app) = \Config::configFile($class);
        \Config::load($app.'::config', true);

        return parent::relations($specific);
    }

    public static function get_primary_key()
    {
        return static::$_primary_key;
    }

    public static function get_table_name()
    {
        return static::$_table_name;
    }

    public static function get_first($options, $preview = false)
    {
        // First argument is a string => it's the virtual name
        if (!is_array($options)) {
            $options = array(
                'where' => array(
                    array('post_virtual_name', '=', $options),
                ),
            );
        }

        if (!$preview) {
            $options['where'][] = array('published', true);
        }

        return static::find('first', $options);
    }

    public static function get_query($params)
    {
        if (!isset($params['query_options'])) {
            $params['query_options'] = array();
        }
        $default_query_options = array(
            'where' => array(
                array('published', true),
            ),
        );
        $params['query_options'] = \Arr::merge($default_query_options, $params['query_options']);
        $query = static::query($params['query_options'])->related(array('author'));

        $query->where(array('post_context', $params['context']));

        if (!empty($params['author'])) {
            $query->where(array('post_author_id', $params['author']->user_id));
        }
        if (!empty($params['tag'])) {
            $query->related(array('tags'));
            $query->where(array('tags.tag_label', $params['tag']->tag_label));
        }
        if (!empty($params['category'])) {
            $query->related(array('categories'));
            $query->where(array('categories.cat_id', $params['category']->cat_id));
        }
        if (!empty($params['categories'])) {
            $query->related(array('categories'));
            $cat_ids = array();
            foreach ($params['categories'] as $category) {
                $cat_ids[] = $category->cat_id;
            }
            $query->where(array('categories.cat_id', 'IN', $cat_ids));
        }
        if (!empty($params['order_by'])) {
            $query->order_by($params['order_by']);
        }
        if (!empty($params['offset'])) {
            $query->rows_offset($params['offset']);
        }
        if (!empty($params['limit'])) {
            $query->rows_limit($params['limit']);
        }

        return $query;
    }

    public static function get_all($params)
    {
        $query = static::get_query($params);

        $posts = static::get_all_from_query($query);

        // Re-fetch with a 2nd request to get all the relations (not only the filtered ones)
        // @todo : to take a look later, see if the orm can't be fixed
        if (!empty($posts) && (!empty($params['tag']) || !empty($params['category']) || !empty($params['categories']))) {
            $posts = static::fetch_relations($posts, $params['order_by']);
        }

        return $posts;
    }

    public static function get_all_from_query($query)
    {
        $posts = $query->get();

        list($app) = \Config::configFile(get_called_class());
        \Config::load($app.'::config', true);
        $withCom = \Config::get($app.'::config.comments.enabled');
        if ($withCom) {
            $posts = static::count_multiple_comments($posts);
        }

        return $posts;
    }

    public static function fetch_relations($posts, $order_by)
    {
        $keys = array_keys((array) $posts);
        $posts = static::query(array(
            'where' => array(
                array('post_id', 'IN', $keys),
            ),
            'order_by' => $order_by,
            'related' => array('author', 'tags', 'categories'),
        ))->get();
        return $posts;
    }

    public static function count_all($params)
    {
        $query = static::get_query($params);

        return $query->count();
    }
}
