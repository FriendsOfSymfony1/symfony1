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
 * User form.
 *
 * @version    SVN: $Id$
 */
class UserForm extends \BaseUserForm
{
    public function configure()
    {
        $profileForm = new \ProfileForm($this->object->getProfile());
        unset($profileForm['id'], $profileForm['user_id']);

        $this->embedForm('Profile', $profileForm);
    }
}
