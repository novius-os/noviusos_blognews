<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

\Nos\I18n::current_dictionary(array('noviusos_blognews::common'));

return array(
    'data_mapping' => array(
        'post_title' => array(
            'title'    => __('Title'),
        ),
        'context' => true,
        'author->user_name' => array(
            'title'         => __('Author'),
            'value' =>  function($item) {
                return !empty($item->author) ? $item->author->fullname() : $item->post_author_alias;
            },
        ),
        'published' => array(
            'title' => __('Status'),
            'dataKey' => 'publication_status',
            'multiContextHide' => true,
        ),
        'post_created_at' => array(
            'title'    => __('Date'),
            'value' =>
                function ($item)
                {
                    if ($item->is_new()) {
                        return null;
                    }
                    return \Date::create_from_string($item->post_created_at, 'mysql')->format('%m/%d/%Y %H:%M:%S'); //%m/%d/%Y %H:%i:%s
                },
            'dataType' => 'datetime',
        ),
        'preview_url' => array(
            'value' => function($item) {
                return $item->preview_url();
            },
        ),
    ),
    'i18n' => array(
        // Crud
        'successfully added' => __('Post successfully added.'),
        'successfully saved' => __('Post successfully saved.'),
        'successfully deleted' => __('The post has successfully been deleted!'),

        // General errors
        'item deleted' => __('This post has been deleted.'),
        'not found' => __('Post not found'),

        // Blank slate
        'error added in context' => __('This post cannot be added {context}.'),
        'item inexistent in context yet' => __('This post has not been added in {context} yet.'),
        'add an item in context' => __('Add a new post in {context}'),

        // Deletion popup
        'delete an item' => __('Delete a post'),
        'you are about to delete, confim' => __('You are about to delete the post <span style="font-weight: bold;">":title"</span>. Are you sure you want to continue?'),
        'you are about to delete' => __('You are about to delete the post <span style="font-weight: bold;">":title"</span>.'),
        'exists in multiple context' => __('This post exists in <strong>{count} contexts</strong>.'),
        'delete in the following contexts' => __('Delete this post in the following contexts:'),
    ),
    'actions' => array(
        'order' => array(
            '{{namespace}}\Model_Post.edit',
            '{{namespace}}\Model_Post.visualise',
            '{{namespace}}\Model_Post.delete',
        ),
    ),
);