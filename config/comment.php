<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

$config = \Config::application('noviusos_blognews');
if (isset($config['comments']['use_recaptcha'])) {
    \Log::deprecated('The key "comments.use_recaptcha" in noviusos_blognews config file is deprecated, '.
        'use key "use_recaptcha" of noviusos_blognews::comment configuration file instead.', 'Chiba.2');
    return array(
        'use_recaptcha' => $config['comments']['use_recaptcha'],
    );
}

return array(
    'use_recaptcha' => false,
);
