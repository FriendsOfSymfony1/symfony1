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
 * sfFormField represents a widget bind to a name and a value.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfFormField
{
    protected static $toStringException;

    /** @var \sfWidgetForm */
    protected $widget;

    /** @var \sfFormField|null */
    protected $parent;

    /** @var string */
    protected $name = '';

    /** @var string */
    protected $value;

    /** @var sfValidatorError|sfValidatorErrorSchema|null */
    protected $error;

    /**
     * Constructor.
     *
     * @param \sfWidgetForm     $widget A sfWidget instance
     * @param \sfFormField      $parent The sfFormField parent instance (null for the root widget)
     * @param string            $name   The field name
     * @param string            $value  The field value
     * @param \sfValidatorError $error  A sfValidatorError instance
     */
    public function __construct(\sfWidgetForm $widget, \sfFormField $parent = null, $name, $value, \sfValidatorError $error = null)
    {
        $this->widget = $widget;
        $this->parent = $parent;
        $this->name = $name;
        $this->value = $value;
        $this->error = $error;
    }

    /**
     * Returns the string representation of this form field.
     *
     * @return string The rendered field
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            self::setToStringException($e);

            // we return a simple Exception message in case the form framework is used out of symfony.
            return 'Exception: '.$e->getMessage();
        }
    }

    /**
     * Returns true if a form thrown an exception in the __toString() method.
     *
     * This is a hack needed because PHP does not allow to throw exceptions in __toString() magic method.
     *
     * @return bool
     */
    public static function hasToStringException()
    {
        return null !== self::$toStringException;
    }

    /**
     * Gets the exception if one was thrown in the __toString() method.
     *
     * This is a hack needed because PHP does not allow to throw exceptions in __toString() magic method.
     *
     * @return \Exception
     */
    public static function getToStringException()
    {
        return self::$toStringException;
    }

    /**
     * Sets an exception thrown by the __toString() method.
     *
     * This is a hack needed because PHP does not allow to throw exceptions in __toString() magic method.
     *
     * @param \Exception $e The exception thrown by __toString()
     */
    public static function setToStringException(\Exception $e)
    {
        if (null === self::$toStringException) {
            self::$toStringException = $e;
        }
    }

    /**
     * Renders the form field.
     *
     * @param array $attributes An array of HTML attributes
     *
     * @return string The rendered widget
     */
    public function render($attributes = [])
    {
        if ($this->parent) {
            return $this->parent->getWidget()->renderField($this->name, $this->value, $attributes, $this->error);
        }

        return $this->widget->render($this->name, $this->value, $attributes, $this->error);
    }

    /**
     * Returns a formatted row.
     *
     * The formatted row will use the parent widget schema formatter.
     * The formatted row contains the label, the field, the error and
     * the help message.
     *
     * @param array  $attributes An array of HTML attributes to merge with the current attributes
     * @param string $label      The label name (not null to override the current value)
     * @param string $help       The help text (not null to override the current value)
     *
     * @return string The formatted row
     */
    public function renderRow($attributes = [], $label = null, $help = null)
    {
        if (null === $this->parent) {
            throw new \LogicException(sprintf('Unable to render the row for "%s".', $this->name));
        }

        $field = $this->parent->getWidget()->renderField($this->name, $this->value, !is_array($attributes) ? [] : $attributes, $this->error);

        $error = $this->error instanceof \sfValidatorErrorSchema ? $this->error->getGlobalErrors() : $this->error;

        $help = null === $help ? $this->parent->getWidget()->getHelp($this->name) : $help;

        return strtr($this->parent->getWidget()->getFormFormatter()->formatRow($this->renderLabel($label), $field, $error, $help), ['%hidden_fields%' => '']);
    }

    /**
     * Returns a formatted error list.
     *
     * The formatted list will use the parent widget schema formatter.
     *
     * @return string The formatted error list
     */
    public function renderError()
    {
        if (null === $this->parent) {
            throw new \LogicException(sprintf('Unable to render the error for "%s".', $this->name));
        }

        $error = $this->getWidget() instanceof \sfWidgetFormSchema ? $this->getWidget()->getGlobalErrors($this->error) : $this->error;

        return $this->parent->getWidget()->getFormFormatter()->formatErrorsForRow($error);
    }

    /**
     * Returns the help text.
     *
     * @return string The help text
     */
    public function renderHelp()
    {
        if (null === $this->parent) {
            throw new \LogicException(sprintf('Unable to render the help for "%s".', $this->name));
        }

        return $this->parent->getWidget()->getFormFormatter()->formatHelp($this->parent->getWidget()->getHelp($this->name));
    }

    /**
     * Returns the label tag.
     *
     * @param string $label      The label name (not null to override the current value)
     * @param array  $attributes Optional html attributes
     *
     * @return string The label tag
     */
    public function renderLabel($label = null, $attributes = [])
    {
        if (null === $this->parent) {
            throw new \LogicException(sprintf('Unable to render the label for "%s".', $this->name));
        }

        if (null !== $label) {
            $currentLabel = $this->parent->getWidget()->getLabel($this->name);
            $this->parent->getWidget()->setLabel($this->name, $label);
        }

        $html = $this->parent->getWidget()->getFormFormatter()->generateLabel($this->name, $attributes);

        if (null !== $label) {
            $this->parent->getWidget()->setLabel($this->name, $currentLabel);
        }

        return $html;
    }

    /**
     * Returns the label name given a widget name.
     *
     * @return string The label name
     */
    public function renderLabelName()
    {
        if (null === $this->parent) {
            throw new \LogicException(sprintf('Unable to render the label name for "%s".', $this->name));
        }

        return $this->parent->getWidget()->getFormFormatter()->generateLabelName($this->name);
    }

    /**
     * Returns the name attribute of the widget.
     *
     * @return string The name attribute of the widget
     */
    public function renderName()
    {
        return $this->parent ? $this->parent->getWidget()->generateName($this->name) : $this->name;
    }

    /**
     * Returns the id attribute of the widget.
     *
     * @return string The id attribute of the widget
     */
    public function renderId()
    {
        return $this->widget->generateId($this->parent ? $this->parent->getWidget()->generateName($this->name) : $this->name, $this->value);
    }

    /**
     * Returns true if the widget is hidden.
     *
     * @return bool true if the widget is hidden, false otherwise
     */
    public function isHidden()
    {
        return $this->widget->isHidden();
    }

    /**
     * Returns the widget name.
     *
     * @return mixed The widget name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the widget value.
     *
     * @return mixed The widget value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the wrapped widget.
     *
     * @return sfWidget|sfWidgetFormSchemaDecorator A sfWidget instance
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * Returns the parent form field.
     *
     * @return \sfFormField A sfFormField instance
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Returns the error for this field.
     *
     * @return \sfValidatorError A sfValidatorError instance
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Returns true is the field has an error.
     *
     * @return bool true if the field has some errors, false otherwise
     */
    public function hasError()
    {
        if ($this->error instanceof \sfValidatorErrorSchema) {
            return $this->error->count() > 0;
        }

        return null !== $this->error;
    }
}
