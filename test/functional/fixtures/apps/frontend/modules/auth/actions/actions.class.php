<?php

/**
 * auth actions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class authActions extends sfActions
{
    public function executeBasic()
    {
        $response = $this->getResponse();

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            $response->setStatusCode(401);
            $response->setHttpHeader('WWW-Authenticate', 'Basic realm="My Realm"');

            $this->auth_user = '';
            $this->auth_password = '';
            $this->msg = 'KO';
        } else {
            $this->auth_user = $_SERVER['PHP_AUTH_USER'];
            $this->auth_password = $_SERVER['PHP_AUTH_PW'];
            $this->msg = 'OK';
        }
    }
}
