<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormSchemaFormatter allows to format a form schema with HTML formats.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfWidgetFormSchemaFormatter
{
    protected static $translationCallable;

    protected $rowFormat = '';
    protected $helpFormat = '%help%';
    protected $errorRowFormat = '%errors%';
    protected $errorListFormatInARow = "  <ul class=\"error_list\">\n%errors%  </ul>\n";
    protected $errorRowFormatInARow = "    <li>%error%</li>\n";
    protected $namedErrorRowFormatInARow = "    <li>%name%: %error%</li>\n";
    protected $decoratorFormat = '';
    protected $widgetSchema;
    protected $translationCatalogue;

    /**
     * Constructor.
     */
    public function __construct(sfWidgetFormSchema $widgetSchema)
    {
        $this->setWidgetSchema($widgetSchema);
    }

    public function formatRow($label, $field, $errors = [], $help = '', $hiddenFields = null)
    {
        return strtr($this->getRowFormat(), [
            '%label%' => $label,
            '%field%' => $field,
            '%error%' => $this->formatErrorsForRow($errors),
            '%help%' => $this->formatHelp($help),
            '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
        ]);
    }

    /**
     * Translates a string using an i18n callable, if it has been provided.
     *
     * @param mixed $subject    The subject to translate
     * @param array $parameters Additional parameters to pass back to the callable
     *
     * @return string
     */
    public function translate($subject, $parameters = [])
    {
        if (false === $subject) {
            return false;
        }

        if (null === self::$translationCallable) {
            // replace object with strings
            foreach ($parameters as $key => $value) {
                if (is_object($value) && method_exists($value, '__toString')) {
                    $parameters[$key] = $value->__toString();
                }
            }

            return strtr($subject, $parameters);
        }

        $catalogue = $this->getTranslationCatalogue();

        if (self::$translationCallable instanceof sfCallable) {
            return self::$translationCallable->call($subject, $parameters, $catalogue);
        }

        return call_user_func(self::$translationCallable, $subject, $parameters, $catalogue);
    }

    /**
     * Returns the current i18n callable.
     */
    public static function getTranslationCallable()
    {
        return self::$translationCallable;
    }

    /**
     * Sets a callable which aims to translate form labels, errors and help messages.
     *
     * @param mixed $callable
     *
     * @throws InvalidArgumentException if an invalid php callable or sfCallable has been provided
     */
    public static function setTranslationCallable($callable)
    {
        if (!$callable instanceof sfCallable && !is_callable($callable)) {
            throw new InvalidArgumentException('Provided i18n callable should be either an instance of sfCallable or a valid PHP callable');
        }

        self::$translationCallable = $callable;
    }

    public function formatHelp($help)
    {
        if (!$help) {
            return '';
        }

        return strtr($this->getHelpFormat(), ['%help%' => $this->translate($help)]);
    }

    public function formatErrorRow($errors)
    {
        if (null === $errors || !$errors) {
            return '';
        }

        return strtr($this->getErrorRowFormat(), ['%errors%' => $this->formatErrorsForRow($errors)]);
    }

    public function formatErrorsForRow($errors)
    {
        if (null === $errors || !$errors) {
            return '';
        }

        if (!is_array($errors)) {
            $errors = [$errors];
        }

        return strtr($this->getErrorListFormatInARow(), ['%errors%' => implode('', $this->unnestErrors($errors))]);
    }

    /**
     * Generates a label for the given field name.
     *
     * @param string $name       The field name
     * @param array  $attributes Optional html attributes for the label tag
     *
     * @return string The label tag
     */
    public function generateLabel($name, $attributes = [])
    {
        $labelName = $this->generateLabelName($name);

        if (false === $labelName) {
            return '';
        }

        if (!isset($attributes['for'])) {
            $attributes['for'] = $this->widgetSchema->generateId($this->widgetSchema->generateName($name));
        }

        return $this->widgetSchema->renderContentTag('label', $labelName, $attributes);
    }

    /**
     * Generates the label name for the given field name.
     *
     * @param string $name The field name
     *
     * @return string The label name
     */
    public function generateLabelName($name)
    {
        $label = $this->widgetSchema->getLabel($name);

        if (!$label && false !== $label) {
            $label = str_replace('_', ' ', ucfirst('_id' == substr($name, -3) ? substr($name, 0, -3) : $name));
        }

        return $this->translate($label);
    }

    /**
     * Get i18n catalogue name.
     *
     * @return string
     */
    public function getTranslationCatalogue()
    {
        return $this->translationCatalogue;
    }

    /**
     * Set an i18n catalogue name.
     *
     * @param string $catalogue
     *
     * @throws InvalidArgumentException when the catalogue is not a string
     */
    public function setTranslationCatalogue($catalogue)
    {
        if (!is_string($catalogue)) {
            throw new InvalidArgumentException('Catalogue name must be a string');
        }

        $this->translationCatalogue = $catalogue;
    }

    public function setRowFormat($format)
    {
        $this->rowFormat = $format;
    }

    public function getRowFormat()
    {
        return $this->rowFormat;
    }

    public function setErrorRowFormat($format)
    {
        $this->errorRowFormat = $format;
    }

    public function getErrorRowFormat()
    {
        return $this->errorRowFormat;
    }

    public function setErrorListFormatInARow($format)
    {
        $this->errorListFormatInARow = $format;
    }

    public function getErrorListFormatInARow()
    {
        return $this->errorListFormatInARow;
    }

    public function setErrorRowFormatInARow($format)
    {
        $this->errorRowFormatInARow = $format;
    }

    public function getErrorRowFormatInARow()
    {
        return $this->errorRowFormatInARow;
    }

    public function setNamedErrorRowFormatInARow($format)
    {
        $this->namedErrorRowFormatInARow = $format;
    }

    public function getNamedErrorRowFormatInARow()
    {
        return $this->namedErrorRowFormatInARow;
    }

    public function setDecoratorFormat($format)
    {
        $this->decoratorFormat = $format;
    }

    public function getDecoratorFormat()
    {
        return $this->decoratorFormat;
    }

    public function setHelpFormat($format)
    {
        $this->helpFormat = $format;
    }

    public function getHelpFormat()
    {
        return $this->helpFormat;
    }

    /**
     * Sets the widget schema associated with this formatter instance.
     *
     * @param sfWidgetFormSchema $widgetSchema A sfWidgetFormSchema instance
     */
    public function setWidgetSchema(sfWidgetFormSchema $widgetSchema)
    {
        $this->widgetSchema = $widgetSchema;
    }

    public function getWidgetSchema()
    {
        return $this->widgetSchema;
    }

    protected function unnestErrors($errors, $prefix = '')
    {
        $newErrors = [];

        foreach ($errors as $name => $error) {
            if ($error instanceof ArrayAccess || is_array($error)) {
                $newErrors = array_merge($newErrors, $this->unnestErrors($error, ($prefix ? $prefix.' > ' : '').$name));
            } else {
                if ($error instanceof sfValidatorError) {
                    $err = $this->translate($error->getMessageFormat(), $error->getArguments());
                } else {
                    $err = $this->translate($error);
                }

                if (!is_int($name)) {
                    $newErrors[] = strtr($this->getNamedErrorRowFormatInARow(), ['%error%' => $err, '%name%' => ($prefix ? $prefix.' > ' : '').$name]);
                } else {
                    $newErrors[] = strtr($this->getErrorRowFormatInARow(), ['%error%' => $err]);
                }
            }
        }

        return $newErrors;
    }
}
