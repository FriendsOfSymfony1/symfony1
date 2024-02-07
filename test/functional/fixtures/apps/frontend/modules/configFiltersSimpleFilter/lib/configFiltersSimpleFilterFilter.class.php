<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class configFiltersSimpleFilterFilter extends \sfFilter
{
    public function execute($filterChain)
    {
        $this->getContext()->getRequest()->setParameter('filter', 'in a filter');

        // execute next filter
        $filterChain->execute();
    }
}
