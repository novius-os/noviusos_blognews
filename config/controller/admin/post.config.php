<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

$datas = array(
    'controller_url'  => 'admin/{{application_name}}/post',
    'model' => '{{namespace}}\Model_Post',
    'i18n_file' => 'noviusos_blognews::post',
    'tab' => array(
        'iconUrl' => 'static/apps/{{application_name}}/img/16/post.png',
        'labels' => array(
            'insert' => __('Add a post'),
            'blankSlate' => __('Translate a post'),
        ),
    ),
    'layout' => array(
        'title' => 'post_title',
        //'id' => 'blog_id',
        'large' => true,
        'medias' => array('medias->thumbnail->medil_media_id'),//'medias->thumbnail->medil_media_id'),

        'save' => 'save',

        'subtitle' => array('post_summary'),

        'content' => array(
            'expander' => array(
                'view' => 'nos::form/expander',
                'params' => array(
                    'title'   => __('Content'),
                    'nomargin' => true,
                    'options' => array(
                        'allowExpand' => false,
                    ),
                    'content' => array(
                        'view' => 'nos::form/fields',
                        'params' => array(
                            'fields' => array(
                                'wysiwygs->content->wysiwyg_text',
                            ),
                        ),
                    ),
                ),
            ),
        ),

        'menu' => array(
            // user_fullname is not a real field in the database
            __('Meta') => array('field_template' => '{field}', 'fields' => array('author->user_fullname', 'post_author_alias', 'post_created_at_date', 'post_created_at_time', 'post_read')),
            __('URL (post address)') => array('post_virtual_name'),
            __('Tags') => array('tags'),
            __('Categories') => array('categories'),
        ),
    ),
    'fields' => array(
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
            'renderer' => 'Nos\Renderer_Virtualname',
            'validation' => array(
                'required',
                'min_length' => array(2),
            ),
        ),
        'author->user_fullname' => array(
            'label' => __('Author: '),
            'renderer' => 'Nos\Renderer_Text',
            'editable' => false,
            'template' => '<p>{label} {field}</p>',
            'dont_populate' => true,
        ),
        'wysiwygs->content->wysiwyg_text' => array(
            'label' => __('Content'),
            'renderer' => 'Nos\Renderer_Wysiwyg',
            'template' => '{field}',
            'form' => array(
                'style' => 'width: 100%; height: 500px;',
            ),
        ),
        'medias->thumbnail->medil_media_id' => array(
            'label' => '',
            'renderer' => 'Nos\Renderer_Media',
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
            'renderer' => 'Nos\Renderer_Date_Picker',
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
            'renderer' => 'Nos\Renderer_Time_Picker',
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
            'renderer' => 'Nos\Renderer_Tag',
            'renderer_options' => array(
                'model'         => '{{namespace}}\\Model_Tag',
                'label_column'  => 'tag_label',
                'relation_name' => 'tags'
            ),
        ),
        'categories' => array(
            'renderer' => 'Nos\BlogNews\Renderer_Selector',
            'renderer_options' => array(
                'height'                => '250px',
                'inspector'             => 'admin/{{application_name}}/inspector/category',
                'model'                 => '{{namespace}}\\Model_Category',
                'multiple'              => '1',
                'main_column'           => 'cat_title',
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

                    $class = get_class($item);
                    $namespace = substr($class, 0, strrpos($class, '\\'));

                    $category_class = $namespace.'\\Model_Category';
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
);

return $datas;
