<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class frontendConfiguration extends \sfApplicationConfiguration
{
    public function configure()
    {
        $this->dispatcher->connect('view.configure_format', [$this, 'configure_format_foo']);
        $this->dispatcher->connect('request.filter_parameters', [$this, 'filter_parameters']);
        $this->dispatcher->connect('view.configure_format', [$this, 'configure_iphone_format']);
    }

    public function filter_parameters(\sfEvent $event, $parameters)
    {
        if (false !== stripos((string) $event->getSubject()->getHttpHeader('user-agent'), 'iPhone')) {
            $event->getSubject()->setRequestFormat('iphone');
        }

        return $parameters;
    }

    public function configure_iphone_format(\sfEvent $event)
    {
        if ('iphone' == $event['format']) {
            $event['response']->addStylesheet('iphone.css');

            $event->getSubject()->setDecorator(true);
        }
    }

    public function configure_format_foo(\sfEvent $event)
    {
        if ('foo' != $event['format']) {
            return;
        }

        $event['response']->setHttpHeader('x-foo', 'true');
        $event->getSubject()->setExtension('.php');
    }
}
