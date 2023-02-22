<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Swift_DoctrineSpool is a spool that uses Doctrine.
 *
 * Example schema:
 *
 *  MailMessage:
 *   actAs: { Timestampable: ~ }
 *   columns:
 *     message: { type: clob, notnull: true }
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class Swift_DoctrineSpool extends Swift_ConfigurableSpool
{
    protected $model;
    protected $column;
    protected $method;

    /**
     * Constructor.
     *
     * @param string The Doctrine model to use to store the messages (MailMessage by default)
     * @param string The column name to use for message storage (message by default)
     * @param string The method to call to retrieve the query to execute (optional)
     * @param mixed $model
     * @param mixed $column
     * @param mixed $method
     */
    public function __construct($model = 'MailMessage', $column = 'message', $method = 'createQuery')
    {
        $this->model = $model;
        $this->column = $column;
        $this->method = $method;
    }

    /**
     * Tests if this Transport mechanism has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Starts this Transport mechanism.
     */
    public function start()
    {
    }

    /**
     * Stops this Transport mechanism.
     */
    public function stop()
    {
    }

    /**
     * Stores a message in the queue.
     *
     * @param Swift_Mime_Message $message The message to store
     */
    public function queueMessage(Swift_Mime_Message $message)
    {
        $object = new $this->model();

        if (!$object instanceof Doctrine_Record) {
            throw new InvalidArgumentException('The mailer message object must be a Doctrine_Record object.');
        }

        $object->{$this->column} = serialize($message);
        $object->save();

        $object->free(true);
    }

    /**
     * Sends messages using the given transport instance.
     *
     * @param Swift_Transport $transport         A transport instance
     * @param string[]        &$failedRecipients An array of failures by-reference
     *
     * @return int The number of sent emails
     */
    public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
    {
        $table = Doctrine_Core::getTable($this->model);
        $objects = $table->{$this->method}()->limit($this->getMessageLimit())->execute();

        if (!$transport->isStarted()) {
            $transport->start();
        }

        $count = 0;
        $time = time();
        foreach ($objects as $object) {
            $message = unserialize($object->{$this->column});

            $object->delete();

            try {
                $count += $transport->send($message, $failedRecipients);
            } catch (Exception $e) {
                // TODO: What to do with errors?
            }

            if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                break;
            }
        }

        return $count;
    }
}
