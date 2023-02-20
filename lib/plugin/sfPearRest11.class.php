<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
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
class sfPearRest11 extends PEAR_REST_11
{
    /**
     * @see PEAR_REST_11
     *
     * @param mixed $config
     * @param mixed $options
     */
    public function __construct($config, $options = array())
    {
        $class = isset($options['base_class']) ? $options['base_class'] : 'sfPearRest';

        $this->_rest = new $class($config, $options);
    }
}
