<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

$current_application = \Nos\Application::getCurrent();
$app_config = \Config::application($current_application);

\Nos\I18n::current_dictionary(array('noviusos_blognews::common'));

$config = array(
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
        'publication_status' => array(
            'title' => __('Status'),
            'column' => 'published',
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
        'thumbnail' => array(
            'value' => function ($item) {
                foreach ($item->medias as $media) {
                    return $media->get_public_path_resized(64, 64);
                }
                return false;
            },
        ),
        'thumbnailAlternate' => array(
            'value' => function ($item) {
                return 'static/novius-os/admin/vendor/jquery/jquery-ui-input-file-thumb/css/images/apn.png';
            }
        ),
    ),
    'i18n' => array(
    ),
    'actions' => array(
        'order' => array(
            '{{namespace}}\Model_Post.edit',
            '{{namespace}}\Model_Post.visualise',
            '{{namespace}}\Model_Post.delete',
        ),
    ),
    'thumbnails' => true,
);

if (!$app_config['authors']['enabled']) {
    unset($config['data_mapping']['author->user_name']);
}

return $config;