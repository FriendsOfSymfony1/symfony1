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
 * sfComponents.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfComponents extends \sfComponent
{
    /**
     * @param \sfRequest $request
     *
     * @throws \sfInitializationException
     *
     * @see \sfComponent
     */
    public function execute($request)
    {
        throw new \sfInitializationException('sfComponents initialization failed.');
    }
}
