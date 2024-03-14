<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Bridge\PhpUnit\DeprecationErrorHandler;

require_once __DIR__.'/../vendor/autoload.php';

if ('disabled' !== getenv('SYMFONY_DEPRECATIONS_HELPER')) {
    DeprecationErrorHandler::register(getenv('SYMFONY_DEPRECATIONS_HELPER'));
}
