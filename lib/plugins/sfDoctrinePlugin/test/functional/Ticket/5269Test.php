<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'frontend';

require_once dirname(__FILE__).'/../../bootstrap/functional.php';

$t = new \lime_test(1);

class TestUserForm extends \UserForm
{
    public function configure()
    {
        parent::configure();
        unset($this['test']);
    }
}

$user = new \User();
$user->username = 'nullvaluetest';
$user->password = 'changeme';
$user->test = 'test';
$user->save();
$user->free();
unset($user);

$user = \Doctrine_Core::getTable('User')->findOneByUsername('nullvaluetest');
$userForm = new \TestUserForm($user);
$userForm->bind(['id' => $user->id, 'username' => 'nullvaluetest', 'password' => 'changeme2']);
if ($userForm->isValid()) {
    $userForm->save();
}

$user->free();
unset($user);

$user = \Doctrine_Core::getTable('User')->findOneByUsername('nullvaluetest');
$t->is($user->toArray(), ['id' => 1, 'username' => 'nullvaluetest', 'password' => 'b0660f0b8b989971524762330aea5449', 'test' => 'test']);
