<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormSchemaDecorator wraps a form schema widget inside a given HTML snippet.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfWidgetFormSchemaDecorator extends sfWidgetFormSchema
{
  protected
    $widget    = null,
    $decorator = '';

  /**
   * Constructor.
   *
   * @param sfWidgetFormSchema $widget     A sfWidgetFormSchema instance
   * @param string             $decorator  A decorator string
   *
   * @see sfWidgetFormSchema
   */
  public function __construct(sfWidgetFormSchema $widget, $decorator)
  {
    $this->widget    = $widget;
    $this->decorator = $decorator;

    parent::__construct();
  }

  /**
   * Returns the decorated widget.
   *
   * @return sfWidget The decorated widget
   */
  public function getWidget()
  {
    return $this->widget;
  }

  /**
   * Renders the widget.
   *
   * @param  string $name       The element name
   * @param  array  $values     The value displayed in this widget
   * @param  array  $attributes An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors     An array of errors for the field
   *
   * @see sfWidget
   *
   * @return string
   */
  public function render($name, $values = array(), $attributes = array(), $errors = array())
  {
    return strtr($this->decorator, array('%content%' => $this->widget->render($name, $values, $attributes, $errors)));
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function addFormFormatter($name, sfWidgetFormSchemaFormatter $formatter)
  {
    $this->widget->addFormFormatter($name, $formatter);

    return $this;
  }

  /**
   * @see sfWidgetFormSchema
   */
  public function getFormFormatters()
  {
    return $this->widget->getFormFormatters();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function setFormFormatterName($name)
  {
    $this->widget->setFormFormatterName($name);

    return $this;
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getFormFormatterName()
  {
    return $this->widget->getFormFormatterName();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getFormFormatter()
  {
    return $this->widget->getFormFormatter();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function setNameFormat($format)
  {
    $this->widget->setNameFormat($format);

    return $this;
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getNameFormat()
  {
    return $this->widget->getNameFormat();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function setLabels(array $labels)
  {
    $this->widget->setLabels($labels);

    return $this;
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getLabels()
  {
    return $this->widget->getLabels();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function setLabel($name, $value = null)
  {
    if (2 == func_num_args())
    {
      $this->widget->setLabel($name, $value);
    }
    else
    {
      $this->widget->setLabel($name);
    }

    return $this;
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getLabel($name = null)
  {
    return 1 == func_num_args() ? $this->widget->getLabel($name) : $this->widget->getLabel();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function setHelps(array $helps)
  {
    $this->widget->setHelps($helps);

    return $this;
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getHelps()
  {
    return $this->widget->getHelps();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function setHelp($name, $help)
  {
    $this->widget->setHelp($name, $help);

    return $this;
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getHelp($name)
  {
    return $this->widget->getHelp($name);
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return $this->widget->getStylesheets();
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    return $this->widget->getJavaScripts();
  }

  /**
   * @see sfWidgetFormSchema
   */
  public function needsMultipartForm()
  {
    return $this->widget->needsMultipartForm();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function renderField($name, $value = null, $attributes = array(), $errors = array())
  {
    return $this->widget->renderField($name, $value, $attributes, $errors);
  }

  /**
   * @see sfWidgetFormSchemaFormatter
   * @inheritdoc
   */
  public function generateLabel($name)
  {
    return $this->widget->getFormFormatter()->generateLabel($name);
  }

  /**
   * @see sfWidgetFormSchemaFormatter
   * @inheritdoc
   */
  public function generateLabelName($name)
  {
    return $this->widget->getFormFormatter()->generateLabelName($name);
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function generateName($name)
  {
    return $this->widget->generateName($name);
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getParent()
  {
    return $this->widget->getParent();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function setParent(sfWidgetFormSchema $parent = null)
  {
    $this->widget->setParent($parent);

    return $this;
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getFields()
  {
    return $this->widget->getFields();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function getPositions()
  {
    return $this->widget->getPositions();
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function setPositions(array $positions)
  {
    $this->widget->setPositions($positions);

    return $this;
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function moveField($field, $action, $pivot = null)
  {
    return $this->widget->moveField($field, $action, $pivot);
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function offsetExists($name)
  {
    return isset($this->widget[$name]);
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function offsetGet($name)
  {
    return $this->widget[$name];
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function offsetSet($name, $widget)
  {
    $this->widget[$name] = $widget;
  }

  /**
   * @see sfWidgetFormSchema
   * @inheritdoc
   */
  public function offsetUnset($name)
  {
    unset($this->widget[$name]);
  }

  public function __clone()
  {
    $this->widget = clone $this->widget;
  }
}
