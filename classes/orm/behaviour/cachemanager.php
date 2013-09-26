<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Nos\BlogNews;

class Orm_Behaviour_Cachemanager extends \Nos\Orm_Behaviour
{
    protected $enhancers;

    public function __construct($class)
    {
        parent::__construct($class);
        $urlEnhancerConfiguration = $class::behaviours('Nos\Orm_Behaviour_Urlenhancer');
        $this->enhancers = $urlEnhancerConfiguration['enhancers'];
    }

    public function after_insert(\Nos\Orm\Model $item)
    {
        \Nos\FrontCache::deleteEnhancersUrls(
            $this->enhancers,
            array(
                '',
                'rss/posts.html',
            ),
            $item->context
        );
    }

    public function after_update(\Nos\Orm\Model $item)
    {
        \Nos\FrontCache::deleteEnhancersUrls(
            $this->enhancers,
            array(
                '',
                'rss/posts.html',
            ),
            $item->context
        );
    }

    public function after_delete(\Nos\Orm\Model $item)
    {
        \Nos\FrontCache::deleteEnhancersUrls(
            $this->enhancers,
            array(
                '',
                'rss/comments.html',
                'rss/posts.html',
                'rss/comments/'.$item->post_virtual_name.'.html'
            ),
            $item->context
        );
    }

    public function deleteCacheComments(\Nos\Orm\Model $item)
    {
        \Nos\FrontCache::deleteEnhancersUrls(
            $this->enhancers,
            array(
                '', // Number of comments
                'rss/comments.html',
                'rss/comments/'.$item->post_virtual_name.'.html'
            ),
            $item->context
        );
    }
}
