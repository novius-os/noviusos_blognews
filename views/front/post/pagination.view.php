<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

echo $pagination->create_links(
    function($page) use ($type, $item) {

        if ($type == 'main') {
            $url = \Nos\Nos::main_controller()->getPageUrl();
            $url_without_suffix = str_replace('.html', '/', $url);
            return $page == 1 ? $url : $url_without_suffix.'page/'.$page.'.html';
        } else {
            return $item->url(array('page' => $page));
        }
    }
);
