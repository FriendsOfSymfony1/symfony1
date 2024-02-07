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
 * HelperHelper.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
function use_helper()
{
    $context = \sfContext::getInstance();

    $context->getConfiguration()->loadHelpers(func_get_args(), $context->getModuleName());
}
