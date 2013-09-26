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

$check_disabled_draft = function ($post) use ($current_application) {
    // Not published => not disabled
    if ($post->planificationStatus() == 0) {
        return false;
    }
    // Published or scheduled => disabled
    return \Nos\User\Permission::atMost($current_application.'::post', '1_draft_only', '2_full_access');
};

$check_disabled_author = function ($post) use ($current_application, $app_config) {

    // When authors are disabled, don't check further and allow the action to be carried out
    if (!$app_config['authors']['enabled']) {
        return false;
    }

    // If user has full access, don't disable the action
    if (\Nos\User\Permission::atLeast($current_application.'::others_post', '3_full_access', '3_full_access')) {
        return false;
    }

    // Disabled when the author is not the current user
    return empty($post->author) || $post->author->user_id != \Session::user()->user_id;
};

$config = array(
    'data_mapping' => array(
        'post_title' => array(
            'title'    => __('Title'),
        ),
        'context' => true,
        'author->user_name' => array(
            'title'         => __('Author'),
            'value' =>  function ($item) {
                return !empty($item->author) ? $item->author->fullname() : $item->post_author_alias;
            },
        ),
        'publication_status' => true,
        'post_created_at' => array(
            'title'    => __('Date'),
            'dataType' => 'datetime',
            // Works without value on Chrome, but not on Firefox
            'value' =>
            function ($item) {
                if ($item->is_new()) {
                    return null;
                }
                return \Date::create_from_string($item->post_created_at, 'mysql')->wijmoFormat();
            },
        ),
        'thumbnail' => array(
            'value' => function ($item) {
                foreach ($item->medias as $media) {
                    return $media->urlResized(64, 64);
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
        'list' => array(
            'edit' => array(
                'disabled' => array(
                    'check_draft' => $check_disabled_draft,
                    'check_author' => $check_disabled_author,
                ),
            ),
            'delete' => array(
                'disabled' => array(
                    'check_draft' => $check_disabled_draft,
                    'check_author' => $check_disabled_author,
                ),
            ),
            'comments' => array(
                'primary' => true,
            ),
        ),
    ),
    'thumbnails' => true,
);

if (empty($app_config['authors']['enabled'])) {
    unset($config['data_mapping']['author->user_name']);
}

if (empty($app_config['comments']['enabled'])) {
    unset($config['actions']['list']['comments']);
    /*$pos = array_search('comments', $config['actions']['order']);
    if ($pos !== false) {
        unset($config['actions']['order'][$pos]);
    }*/
}

return $config;
