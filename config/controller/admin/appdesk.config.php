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

$config = array(
    'model' => '{{namespace}}\Model_Post',
    'toolbar' => array(
        'models' => array('{{namespace}}\Model_Post')
    ),
    'query' => array(
        'model' => '{{namespace}}\Model_Post',
        'related' => array('linked_medias'),
        'order_by' => array('post_created_at' => 'DESC'),
        'limit' => 20,
        'callback' => array(
            'restrict_other_authors_posts' => function ($query) use ($current_application, $app_config) {
                if ($app_config['authors']['enabled'] && \Nos\User\Permission::atMost($current_application.'::others_post', '1_no_access', '3_full_access')) {
                    $query->where('post_author_id', \Session::user()->user_id);
                }
                return $query;
            },
        ),
    ),
    'search_text' => 'post_title',
    'thumbnails' => true,
);

$config['inspectors'] = array();

if ($app_config['authors']['enabled']) {
    $config['inspectors'][] = 'author';
}
if ($app_config['tags']['enabled']) {
    $config['inspectors'][] = 'tag';
}
if ($app_config['categories']['enabled']) {
    $config['inspectors'][] = 'category';
    $config['toolbar']['models'][] = '{{namespace}}\Model_Category';
}
$config['inspectors'][] = 'date';

return $config;
