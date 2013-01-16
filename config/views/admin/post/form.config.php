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

$datas = array(
    'title' => 'post_title',
    //'id' => 'blog_id',
    'large' => true,
    'medias' => array('medias->thumbnail->medil_media_id'),//'medias->thumbnail->medil_media_id'),

    'save' => 'save',

    'subtitle' => array('post_summary'),

    'content' => array(
        View::forge('form/expander', array(
            'title'   => __('Content'),
            'nomargin' => true,
            'content' => '',
            'options' => array(
                'allowExpand' => false,
            ),
        ), false),
    ),

    'menu' => array(
        // user_fullname is not a real field in the database
        __('Properties') => array('field_template' => '{field}', 'fields' => array('author->user_fullname', 'post_author_alias', 'post_created_at_date', 'post_created_at_time', 'post_read')),
        __('URL (post address)') => array('post_virtual_name'),
        __('Tags') => array('tags'),
        __('Categories') => array('categories'),
    ),
);

if (!\Config::get('noviusos_news::config.summary.enabled')) {
    unset($datas['subtitle']);
}
if (!\Config::get('noviusos_news::config.tags.enabled')) {
    unset($datas['menu'][__('Tags')]);
}
if (!\Config::get('noviusos_news::config.categories.enabled')) {
    unset($datas['menu'][__('Categories')]);
}
if (!\Config::get('noviusos_news::config.authors.enabled')) {
    $datas['menu'][__('Meta')] = array('field_template' => '{field}', 'fields' => array('post_created_at_date', 'post_created_at_time', 'post_read'));
}
return $datas;
