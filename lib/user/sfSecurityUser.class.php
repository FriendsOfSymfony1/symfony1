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
 * sfSecurityUser interface provides advanced security manipulation methods.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 *
 * @version    SVN: $Id$
 */
interface sfSecurityUser
{
    /**
     * Add a credential to this user.
     *
     * @param mixed $credential credential data
     */
    public function addCredential($credential);

    /**
     * Clear all credentials associated with this user.
     */
    public function clearCredentials();

    /**
     * Indicates whether or not this user has a credential.
     *
     * @param mixed $credential credential data
     *
     * @return bool true, if this user has the credential, otherwise false
     */
    public function hasCredential($credential);

    /**
     * Indicates whether or not this user is authenticated.
     *
     * @return bool true, if this user is authenticated, otherwise false
     */
    public function isAuthenticated();

    /**
     * Remove a credential from this user.
     *
     * @param mixed $credential credential data
     */
    public function removeCredential($credential);

    /**
     * Set the authenticated status of this user.
     *
     * @param bool $authenticated a flag indicating the authenticated status of this user
     */
    public function setAuthenticated($authenticated);
}
