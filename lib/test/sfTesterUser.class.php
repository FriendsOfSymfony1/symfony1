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
 * sfTesterUser implements tests for the symfony user object.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfTesterUser extends \sfTester
{
    protected $user;

    /**
     * Prepares the tester.
     */
    public function prepare()
    {
    }

    /**
     * Initializes the tester.
     */
    public function initialize()
    {
        $this->user = $this->browser->getUser();
    }

    /**
     * Tests a user attribute value.
     *
     * @param string $key
     * @param string $value
     * @param string $ns
     *
     * @return sfTester|sfTestFunctionalBase
     */
    public function isAttribute($key, $value, $ns = null)
    {
        $this->tester->is($this->user->getAttribute($key, null, $ns), $value, sprintf('user attribute "%s" is "%s"', $key, $value));

        return $this->getObjectToReturn();
    }

    /**
     * Tests a user flash value.
     *
     * @param string $key
     * @param string $value
     *
     * @return sfTester|sfTestFunctionalBase
     */
    public function isFlash($key, $value)
    {
        $this->tester->is($this->user->getFlash($key), $value, sprintf('user flash "%s" is "%s"', $key, $value));

        return $this->getObjectToReturn();
    }

    /**
     * Tests the user culture.
     *
     * @param string $culture The user culture
     *
     * @return sfTester|sfTestFunctionalBase
     */
    public function isCulture($culture)
    {
        $this->tester->is($this->user->getCulture(), $culture, sprintf('user culture is "%s"', $culture));

        return $this->getObjectToReturn();
    }

    /**
     * Tests if the user is authenticated.
     *
     * @param bool $boolean Whether to check if the user is authenticated or not
     *
     * @return sfTester|sfTestFunctionalBase
     */
    public function isAuthenticated($boolean = true)
    {
        $this->tester->is($this->user->isAuthenticated(), $boolean, sprintf('user is %sauthenticated', $boolean ? '' : 'not '));

        return $this->getObjectToReturn();
    }

    /**
     * Tests if the user has some credentials.
     *
     * @param bool $boolean Whether to check if the user have some credentials or not
     * @param bool $useAnd  specify the mode, either AND or OR
     *
     * @return sfTester|sfTestFunctionalBase
     */
    public function hasCredential($credentials, $boolean = true, $useAnd = true)
    {
        $this->tester->is($this->user->hasCredential($credentials, $useAnd), $boolean, sprintf('user has %sthe right credentials', $boolean ? '' : 'not '));

        return $this->getObjectToReturn();
    }
}
