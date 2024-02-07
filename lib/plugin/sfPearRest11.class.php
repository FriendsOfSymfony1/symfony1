<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'PEAR/REST/11.php';

/**
 * sfPearRest10 interacts with a PEAR channel that supports REST 1.1.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfPearRest11 extends \PEAR_REST_11
{
    /**
     * @see \PEAR_REST_11
     */
    public function __construct($config, $options = [])
    {
        $class = isset($options['base_class']) ? $options['base_class'] : 'sfPearRest';

        $this->_rest = new $class($config, $options);
    }
}
