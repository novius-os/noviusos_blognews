<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */


if ($blognews_config['authors']['enabled'] && $blognews_config['authors']['show']) {
    \Nos\I18n::current_dictionary(array('noviusos_blognews::front'));
    ?>
    <div class="blognews_author">
    <?php
    if (!empty($item->author)) {
        echo $item->author->htmlAnchor(array(
            'text' => e(strtr(__('Author: {{author}}'), array('{{author}}' => $item->author->fullname())))
        ));
    } else {
        echo e(strtr(__('Author: {{author}}'), array('{{author}}' => $item->post_author_alias)));
    }
    ?>
    </div>
    <?php
}
