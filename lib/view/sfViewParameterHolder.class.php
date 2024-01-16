<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfViewParameterHolder stores all variables that will be available to the template.
 *
 * It can also escape variables with an escaping method.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfViewParameterHolder extends sfParameterHolder
{
    protected $dispatcher;
    protected $escaping;
    protected $escapingMethod;

    /**
     * Constructor.
     *
     * @param mixed $parameters
     * @param mixed $options
     */
    public function __construct(sfEventDispatcher $dispatcher, $parameters = [], $options = [])
    {
        $this->initialize($dispatcher, $parameters, $options);
    }

    /**
     * Serializes the current instance for PHP 7.4+.
     *
     * @return array
     */
    public function __serialize()
    {
        return [$this->getAll(), $this->escapingMethod, $this->escaping];
    }

    /**
     * Unserializes a sfParameterHolder instance for PHP 7.4+.
     *
     * @param array $data
     */
    public function __unserialize($data)
    {
        list($this->parameters, $escapingMethod, $escaping) = $data;
        $this->initialize(sfContext::hasInstance() ? sfContext::getInstance()->getEventDispatcher() : new sfEventDispatcher());

        $this->setEscapingMethod($escapingMethod);
        $this->setEscaping($escaping);
    }

    /**
     * Initializes this view parameter holder.
     *
     * @param sfEventDispatcher $dispatcher an sfEventDispatcher instance
     * @param array             $parameters an associative array of initialization parameters
     * @param array             $options    An associative array of options.
     *
     * <b>Options:</b>
     *
     * # <b>escaping_strategy</b> - [off]              - The escaping strategy (on or off)
     * # <b>escaping_method</b>   - [ESC_SPECIALCHARS] - The escaping method (ESC_RAW, ESC_ENTITIES, ESC_JS, ESC_JS_NO_ENTITIES, or ESC_SPECIALCHARS)
     *
     * @return bool true, if initialization completes successfully, otherwise false
     *
     * @throws sfInitializationException if an error occurs while initializing this view parameter holder
     */
    public function initialize(sfEventDispatcher $dispatcher, $parameters = [], $options = [])
    {
        $this->dispatcher = $dispatcher;

        $this->add($parameters);

        $this->setEscaping(isset($options['escaping_strategy']) ? $options['escaping_strategy'] : false);
        $this->setEscapingMethod(isset($options['escaping_method']) ? $options['escaping_method'] : 'ESC_SPECIALCHARS');
    }

    /**
     * Returns true if the current object acts as an escaper.
     *
     * @return bool true if the current object acts as an escaper, false otherwise
     */
    public function isEscaped()
    {
        return in_array($this->getEscaping(), ['on', 'true', true], true);
    }

    /**
     * Returns an array representation of the view parameters.
     *
     * @return array An array of view parameters
     *
     * @throws InvalidArgumentException
     */
    public function toArray()
    {
        $event = $this->dispatcher->filter(new sfEvent($this, 'template.filter_parameters'), $this->getAll());
        $parameters = $event->getReturnValue();
        $attributes = [];

        if ($this->isEscaped()) {
            $attributes['sf_data'] = sfOutputEscaper::escape($this->getEscapingMethod(), $parameters);
            foreach ($attributes['sf_data'] as $key => $value) {
                $attributes[$key] = $value;
            }
        } elseif (in_array($this->getEscaping(), ['off', false], true)) {
            $attributes = $parameters;
            $attributes['sf_data'] = sfOutputEscaper::escape(ESC_RAW, $parameters);
        } else {
            throw new InvalidArgumentException(sprintf('Unknown strategy "%s".', $this->getEscaping()));
        }

        return $attributes;
    }

    /**
     * Gets the default escaping strategy associated with this view.
     *
     * The escaping strategy specifies how the variables get passed to the view.
     *
     * @return string the escaping strategy
     */
    public function getEscaping()
    {
        return $this->escaping;
    }

    /**
     * Sets the escape character strategy.
     *
     * @param string $escaping Escape code
     */
    public function setEscaping($escaping)
    {
        $this->escaping = $escaping;
    }

    /**
     * Returns the name of the function that is to be used as the escaping method.
     *
     * If the escaping method is empty, then that is returned. The default value
     * specified by the sub-class will be used. If the method does not exist (in
     * the sense there is no define associated with the method), an exception is
     * thrown.
     *
     * @return string The escaping method as the name of the function to use
     *
     * @throws InvalidArgumentException If the method does not exist
     */
    public function getEscapingMethod()
    {
        if (empty($this->escapingMethod)) {
            return $this->escapingMethod;
        }

        if (!defined($this->escapingMethod)) {
            throw new InvalidArgumentException(sprintf('The escaping method "%s" is not available.', $this->escapingMethod));
        }

        return constant($this->escapingMethod);
    }

    /**
     * Sets the escaping method for the current view.
     *
     * @param string $method Method for escaping
     */
    public function setEscapingMethod($method)
    {
        $this->escapingMethod = $method;
    }

    /**
     * Serializes the current instance.
     *
     * @return string Objects instance
     */
    public function serialize()
    {
        return serialize($this->__serialize());
    }

    /**
     * Unserializes a sfViewParameterHolder instance.
     *
     * @param string $serialized The serialized instance data
     */
    public function unserialize($serialized)
    {
        $this->__unserialize(unserialize($serialized));
    }
}
