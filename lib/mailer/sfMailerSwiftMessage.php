<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class_exists('Swift');

if (version_compare(Swift::VERSION, '6.0.0') >= 0) {
    class_alias('Swift_Mime_SimpleMessage', 'sfMailerSwiftMessage');
} else {
    class_alias('Swift_Mime_Message', 'sfMailerSwiftMessage');
}
