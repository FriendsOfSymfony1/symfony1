<?php

/**
 * User form.
 */
class UserForm extends BaseUserForm
{
    public function configure()
    {
        $profileForm = new ProfileForm($this->object->getProfile());
        unset($profileForm['id'], $profileForm['user_id']);

        $this->embedForm('Profile', $profileForm);
    }
}
