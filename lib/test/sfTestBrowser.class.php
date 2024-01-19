<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/lime/lime.php';

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfTestBrowser simulates a browser which can test a symfony application.
 *
 * sfTestFunctional is backward compatible class for symfony 1.0, and 1.1.
 * For new code, you can use the sfTestFunctional class directly.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfTestBrowser extends \sfTestFunctional
{
    /**
     * Initializes the browser tester instance.
     *
     * @param string $hostname Hostname to browse
     * @param string $remote   Remote address to spook
     * @param array  $options  Options for sfBrowser
     */
    public function __construct($hostname = null, $remote = null, $options = [])
    {
        if (is_object($hostname)) {
            // new signature
            parent::__construct($hostname, $remote);
        } else {
            $browser = new \sfBrowser($hostname, $remote, $options);

            if (null === self::$test) {
                $lime = new \lime_test(null, isset($options['output']) ? $options['output'] : null);
            } else {
                $lime = null;
            }

            parent::__construct($browser, $lime);
        }
    }
}
