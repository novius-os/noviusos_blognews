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

return array(
    'behaviours' => array (
        'Nos\Orm_Behaviour_Publishable' => array(
            'options' => array(
                'allow_publish' => array(
                    'check_draft' => function () use ($current_application) {
                        return \Nos\User\Permission::atLeast($current_application.'::post', '2_full_access', '2_full_access');
                    },
                ),
            ),
        ),
    ),
);
