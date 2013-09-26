<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

$tag = empty($context) || $context != 'show' ? 'h2' : 'h1';

// Always display the link in the list
if ($tag == 'h1' && empty($enhancer_args['link_on_title'])) {
    $content = e($item->post_title);
} else {
    $content = $item->htmlAnchor();
}
echo '<', $tag, ' class="blognews_title">', $content, '</', $tag, '>';
