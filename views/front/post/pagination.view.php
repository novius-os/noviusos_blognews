<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

if (!isset($pagination_callback)) {
    $pagination_callback = function ($page) use ($type, $item) {
        if ($type == 'main') {
            $main_controller = \Nos\Nos::main_controller();
            $url = parse_url($main_controller->getContextUrl(), PHP_URL_PATH).$main_controller->getPageUrl();
            return $page == 1 ? $url : str_replace('.html', '/', $url).'page/'.$page.'.html';
        } else {
            return $item->url(array('page' => $page));
        }
    };
    \Log::deprecated(
        'The view "noviusos_blognews::front/pagination" without a $pagination_callback parameter is deprecated.',
        '4.2 (Dubrovka)'
    );
}
echo $pagination->create_links($pagination_callback);
