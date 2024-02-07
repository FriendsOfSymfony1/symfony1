<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

echo json_encode([
    'error' => [
        'code' => $code,
        'message' => $message,
        'debug' => [
            'name' => $name,
            'message' => $message,
            'traces' => $traces,
        ],
    ]]);
