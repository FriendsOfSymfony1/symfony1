<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * cache components.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class cacheComponents extends \sfComponents
{
    public function executeComponent()
    {
        $this->componentParam = 'componentParam';
        $this->requestParam = $this->getRequestParameter('param');
    }

    public function executeCacheableComponent()
    {
        $this->componentParam = 'componentParam';
        $this->requestParam = $this->getRequestParameter('param');
    }

    public function executeContextualComponent()
    {
        $this->componentParam = 'componentParam';
        $this->requestParam = $this->getRequestParameter('param');
    }

    public function executeContextualCacheableComponent()
    {
        $this->componentParam = 'componentParam';
        $this->requestParam = $this->getRequestParameter('param');
    }
}
