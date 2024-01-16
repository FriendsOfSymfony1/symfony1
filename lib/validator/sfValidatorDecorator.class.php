<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorDecorator decorates another validator.
 *
 * This validator has exactly the same behavior as the Decorator validator.
 *
 * The options and messages are proxied from the decorated validator.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfValidatorDecorator extends sfValidatorBase
{
    protected $validator;

    /**
     * @see sfValidatorBase
     *
     * @param mixed $options
     * @param mixed $messages
     */
    public function __construct($options = [], $messages = [])
    {
        $this->validator = $this->getValidator();

        if (!$this->validator instanceof sfValidatorBase) {
            throw new RuntimeException('The getValidator() method must return a sfValidatorBase instance.');
        }

        foreach ($options as $key => $value) {
            $this->validator->setOption($key, $value);
        }

        foreach ($messages as $key => $value) {
            $this->validator->setMessage($key, $value);
        }
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $value
     */
    public function clean($value)
    {
        return $this->doClean($value);
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $name
     */
    public function getMessage($name)
    {
        return $this->validator->getMessage($name);
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function setMessage($name, $value)
    {
        $this->validator->setMessage($name, $value);
    }

    /**
     * @see sfValidatorBase
     */
    public function getMessages()
    {
        return $this->validator->getMessages();
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $values
     */
    public function setMessages($values)
    {
        return $this->validator->setMessages($values);
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $name
     */
    public function getOption($name)
    {
        return $this->validator->getOption($name);
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function setOption($name, $value)
    {
        $this->validator->setOption($name, $value);
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $name
     */
    public function hasOption($name)
    {
        return $this->validator->hasOption($name);
    }

    /**
     * @see sfValidatorBase
     */
    public function getOptions()
    {
        return $this->validator->getOptions();
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $values
     */
    public function setOptions($values)
    {
        $this->validator->setOptions($values);
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $indent
     */
    public function asString($indent = 0)
    {
        return $this->validator->asString($indent);
    }

    /**
     * @see sfValidatorBase
     */
    public function getDefaultOptions()
    {
        return $this->validator->getDefaultOptions();
    }

    /**
     * @see sfValidatorBase
     */
    public function getDefaultMessages()
    {
        return $this->validator->getDefaultMessages();
    }

    /**
     * Returns the decorated validator.
     *
     * Every subclass must implement this method.
     *
     * @return sfValidatorBase A sfValidatorBase instance
     */
    abstract protected function getValidator();

    /**
     * @see sfValidatorBase
     *
     * @param mixed $value
     */
    protected function doClean($value)
    {
        return $this->validator->clean($value);
    }
}
