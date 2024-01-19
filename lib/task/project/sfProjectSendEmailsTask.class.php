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
 * Send emails stored in a queue.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfProjectSendEmailsTask extends \sfBaseTask
{
    /**
     * @see \sfTask
     */
    protected function configure()
    {
        $this->addOptions([
            new \sfCommandOption('application', null, \sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
            new \sfCommandOption('env', null, \sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new \sfCommandOption('message-limit', null, \sfCommandOption::PARAMETER_OPTIONAL, 'The maximum number of messages to send', 0),
            new \sfCommandOption('time-limit', null, \sfCommandOption::PARAMETER_OPTIONAL, 'The time limit for sending messages (in seconds)', 0),
        ]);

        $this->namespace = 'project';
        $this->name = 'send-emails';

        $this->briefDescription = 'Sends emails stored in a queue';

        $this->detailedDescription = <<<'EOF'
The [project:send-emails|INFO] sends emails stored in a queue:

  [php symfony project:send-emails|INFO]

You can limit the number of messages to send:

  [php symfony project:send-emails --message-limit=10|INFO]

Or limit to time (in seconds):

  [php symfony project:send-emails --time-limit=10|INFO]
EOF;
    }

    protected function execute($arguments = [], $options = [])
    {
        $databaseManager = new \sfDatabaseManager($this->configuration);

        $spool = $this->getMailer()->getSpool();
        $spool->setMessageLimit($options['message-limit']);
        $spool->setTimeLimit($options['time-limit']);

        $sent = $this->getMailer()->flushQueue();

        $this->logSection('project', sprintf('sent %s emails', $sent));
    }
}
