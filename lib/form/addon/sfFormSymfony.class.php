<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Extends the form component with symfony-specific functionality.
 *
 * @author     Kris Wallsmith <kris.wallsmith@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfFormSymfony extends sfForm
{
    /** @var sfEventDispatcher|null */
    protected static $dispatcher;

    /**
     * Constructor.
     *
     * Notifies the 'form.post_configure' event.
     *
     * @see sfForm
     *
     * @param mixed|null $CSRFSecret
     * @param mixed      $defaults
     * @param mixed      $options
     */
    public function __construct($defaults = [], $options = [], $CSRFSecret = null)
    {
        parent::__construct($defaults, $options, $CSRFSecret);

        if (self::$dispatcher) {
            self::$dispatcher->notify(new sfEvent($this, 'form.post_configure'));
        }
    }

    /**
     * Calls methods defined via sfEventDispatcher.
     *
     * @param string $method    The method name
     * @param array  $arguments The method arguments
     *
     * @return mixed The returned value of the called method
     *
     * @throws sfException
     */
    public function __call($method, $arguments)
    {
        if (self::$dispatcher) {
            $event = self::$dispatcher->notifyUntil(new sfEvent($this, 'form.method_not_found', ['method' => $method, 'arguments' => $arguments]));
            if ($event->isProcessed()) {
                return $event->getReturnValue();
            }
        }

        throw new sfException(sprintf('Call to undefined method %s::%s.', get_class($this), $method));
    }

    /**
     * Sets the event dispatcher to be used by all forms.
     */
    public static function setEventDispatcher(sfEventDispatcher $dispatcher = null)
    {
        self::$dispatcher = $dispatcher;
    }

    /**
     * Returns the event dispatcher.
     *
     * @return sfEventDispatcher
     */
    public static function getEventDispatcher()
    {
        return self::$dispatcher;
    }

    /**
     * Notifies the 'form.filter_values' and 'form.validation_error' events.
     *
     * @see sfForm
     */
    protected function doBind(array $values)
    {
        if (self::$dispatcher) {
            $values = self::$dispatcher->filter(new sfEvent($this, 'form.filter_values'), $values)->getReturnValue();
        }

        try {
            parent::doBind($values);
        } catch (sfValidatorError $error) {
            if (self::$dispatcher) {
                self::$dispatcher->notify(new sfEvent($this, 'form.validation_error', ['error' => $error]));
            }

            throw $error;
        }
    }
}
