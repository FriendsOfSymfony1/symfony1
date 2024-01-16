<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2004-2006 Sean Kerr <sean@code-box.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfBasicSecurityUser will handle any type of data as a credential.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 *
 * @version    SVN: $Id$
 */
class sfBasicSecurityUser extends sfUser implements sfSecurityUser
{
    public const LAST_REQUEST_NAMESPACE = 'symfony/user/sfUser/lastRequest';
    public const AUTH_NAMESPACE = 'symfony/user/sfUser/authenticated';
    public const CREDENTIAL_NAMESPACE = 'symfony/user/sfUser/credentials';

    protected $lastRequest;

    protected $credentials;
    protected $authenticated;

    protected $timedout = false;

    /**
     * Clears all credentials.
     */
    public function clearCredentials()
    {
        $this->credentials = [];
    }

    /**
     * Returns the current user's credentials.
     *
     * @return array
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Removes a credential.
     *
     * @param mixed $credential credential
     */
    public function removeCredential($credential)
    {
        if ($this->hasCredential($credential)) {
            foreach ($this->credentials as $key => $value) {
                if ($credential == $value) {
                    if ($this->options['logging']) {
                        $this->dispatcher->notify(new sfEvent($this, 'application.log', [sprintf('Remove credential "%s"', $credential)]));
                    }

                    unset($this->credentials[$key]);

                    $this->storage->regenerate(true);

                    return;
                }
            }
        }
    }

    /**
     * Adds a credential.
     *
     * @param mixed $credential
     */
    public function addCredential($credential)
    {
        $this->addCredentials(func_get_args());
    }

    /**
     * Adds several credential at once.
     *
     * @param  mixed array or list of credentials
     */
    public function addCredentials()
    {
        if (0 == func_num_args()) {
            return;
        }

        // Add all credentials
        $credentials = (is_array(func_get_arg(0))) ? func_get_arg(0) : func_get_args();

        if ($this->options['logging']) {
            $this->dispatcher->notify(new sfEvent($this, 'application.log', [sprintf('Add credential(s) "%s"', implode(', ', $credentials))]));
        }

        $added = false;
        foreach ($credentials as $aCredential) {
            if (!in_array($aCredential, $this->credentials)) {
                $added = true;
                $this->credentials[] = $aCredential;
            }
        }

        if ($added) {
            $this->storage->regenerate(true);
        }
    }

    /**
     * Returns true if user has credential.
     *
     * @param bool  $useAnd      specify the mode, either AND or OR
     * @param mixed $credentials
     *
     * @return bool
     *
     * @author Olivier Verdier <Olivier.Verdier@free.fr>
     */
    public function hasCredential($credentials, $useAnd = true)
    {
        if (null === $this->credentials) {
            return false;
        }

        if (!is_array($credentials)) {
            return in_array($credentials, $this->credentials);
        }

        // now we assume that $credentials is an array
        $test = false;

        foreach ($credentials as $credential) {
            // recursively check the credential with a switched AND/OR mode
            $test = $this->hasCredential($credential, $useAnd ? false : true);

            if ($useAnd) {
                $test = $test ? false : true;
            }

            if ($test) { // either passed one in OR mode or failed one in AND mode
                break; // the matter is settled
            }
        }

        if ($useAnd) { // in AND mode we succeed if $test is false
            $test = $test ? false : true;
        }

        return $test;
    }

    /**
     * Returns true if user is authenticated.
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->authenticated;
    }

    /**
     * Sets authentication for user.
     *
     * @param bool $authenticated
     */
    public function setAuthenticated($authenticated)
    {
        if ($this->options['logging']) {
            $this->dispatcher->notify(new sfEvent($this, 'application.log', [sprintf('User is %sauthenticated', true === $authenticated ? '' : 'not ')]));
        }

        if ((bool) $authenticated !== $this->authenticated) {
            if (true === $authenticated) {
                $this->authenticated = true;
            } else {
                $this->authenticated = false;
                $this->clearCredentials();
            }

            $this->dispatcher->notify(new sfEvent($this, 'user.change_authentication', ['authenticated' => $this->authenticated]));

            $this->storage->regenerate(true);
        }
    }

    public function setTimedOut()
    {
        $this->timedout = true;
    }

    public function isTimedOut()
    {
        return $this->timedout;
    }

    /**
     * Returns the timestamp of the last user request.
     *
     * @return int
     */
    public function getLastRequestTime()
    {
        return $this->lastRequest;
    }

    /**
     * Available options:.
     *
     *  * timeout: Timeout to automatically log out the user in seconds (1800 by default)
     *             Set to false to disable
     *
     * @param sfEventDispatcher $dispatcher an sfEventDispatcher instance
     * @param sfStorage         $storage    an sfStorage instance
     * @param array             $options    an associative array of options
     *
     * @see sfUser
     */
    public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = [])
    {
        // initialize parent
        parent::initialize($dispatcher, $storage, $options);

        if (!array_key_exists('timeout', $this->options)) {
            $this->options['timeout'] = 1800;
        }

        // read data from storage
        $this->authenticated = $storage->read(self::AUTH_NAMESPACE);
        $this->credentials = $storage->read(self::CREDENTIAL_NAMESPACE);
        $this->lastRequest = $storage->read(self::LAST_REQUEST_NAMESPACE);

        if (null === $this->authenticated) {
            $this->authenticated = false;
            $this->credentials = [];
        } else {
            // Automatic logout logged in user if no request within timeout parameter seconds
            $timeout = $this->options['timeout'];
            if (false !== $timeout && null !== $this->lastRequest && time() - $this->lastRequest >= $timeout) {
                if ($this->options['logging']) {
                    $this->dispatcher->notify(new sfEvent($this, 'application.log', ['Automatic user logout due to timeout']));
                }

                $this->setTimedOut();
                $this->setAuthenticated(false);
            }
        }

        $this->lastRequest = time();
    }

    public function shutdown()
    {
        // write the last request time to the storage
        $this->storage->write(self::LAST_REQUEST_NAMESPACE, $this->lastRequest);

        $this->storage->write(self::AUTH_NAMESPACE, $this->authenticated);
        $this->storage->write(self::CREDENTIAL_NAMESPACE, $this->credentials);

        // call the parent shutdown method
        parent::shutdown();
    }
}
