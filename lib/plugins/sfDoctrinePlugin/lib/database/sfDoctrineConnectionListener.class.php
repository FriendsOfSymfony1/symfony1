<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Standard connection listener.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 */
class sfDoctrineConnectionListener extends Doctrine_EventListener
{
    protected $connection;
    protected $encoding;

    public function __construct($connection, $encoding)
    {
        $this->connection = $connection;
        $this->encoding = $encoding;
    }

    public function postConnect(Doctrine_Event $event)
    {
        $this->connection->setCharset($this->encoding);
        $this->connection->setDateFormat();
    }
}
