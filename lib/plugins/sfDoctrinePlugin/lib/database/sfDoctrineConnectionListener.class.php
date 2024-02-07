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
 * Standard connection listener.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrineConnectionListener extends \Doctrine_EventListener
{
    protected $connection;
    protected $encoding;

    public function __construct($connection, $encoding)
    {
        $this->connection = $connection;
        $this->encoding = $encoding;
    }

    public function postConnect(\Doctrine_Event $event)
    {
        $this->connection->setCharset($this->encoding);
        $this->connection->setDateFormat();
    }
}
