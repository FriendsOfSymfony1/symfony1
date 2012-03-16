[?php

/**
 * <?php echo $this->modelName ?> form base class.
 *
 * @method <?php echo $this->modelName ?> getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id$
 */
abstract class Base<?php echo $this->modelName ?>Form extends <?php echo $this->getFormClassToExtend().PHP_EOL ?>
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

<?php foreach ($this->getColumns() as $column): ?>
    $this->widgetSchema   ['<?php echo $column->getFieldName() ?>'] = new <?php echo $this->getWidgetClassForColumn($column) ?>(<?php echo $this->getWidgetOptionsForColumn($column) ?>);
    $this->validatorSchema['<?php echo $column->getFieldName() ?>'] = new <?php echo $this->getValidatorClassForColumn($column) ?>(<?php echo $this->getValidatorOptionsForColumn($column) ?>);

<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
    $this->widgetSchema   ['<?php echo $this->underscore($relation['alias']) ?>_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>'));
    $this->validatorSchema['<?php echo $this->underscore($relation['alias']) ?>_list'] = new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>', 'required' => false));

<?php endforeach; ?>
    $this->widgetSchema->setNameFormat('<?php echo $this->underscore($this->modelName) ?>[%s]');
  }

  public function getModelName()
  {
    return '<?php echo $this->modelName ?>';
  }

<?php if ($this->getManyToManyRelations()): ?>
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

<?php foreach ($this->getManyToManyRelations() as $relation): ?>
    if (isset($this->widgetSchema['<?php echo $this->underscore($relation['alias']) ?>_list']))
    {
      $this->setDefault('<?php echo $this->underscore($relation['alias']) ?>_list', $this->object-><?php echo $relation['alias']; ?>->getPrimaryKeys());
    }

<?php endforeach; ?>
  }

  protected function doUpdateObject($values)
  {
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
    $this->update<?php echo $relation['alias'] ?>List($values);
<?php endforeach; ?>

    parent::doUpdateObject($values);
  }

<?php foreach ($this->getManyToManyRelations() as $relation): ?>
  public function update<?php echo $relation['alias'] ?>List($values)
  {
    if (!isset($this->widgetSchema['<?php echo $this->underscore($relation['alias']) ?>_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (!array_key_exists('<?php echo $this->underscore($relation['alias']) ?>_list', $values))
    {
      // no values for this widget
      return;
    }

    $existing = $this->object-><?php echo $relation['alias']; ?>->getPrimaryKeys();
    $values = $values['<?php echo $this->underscore($relation['alias']) ?>_list'];
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('<?php echo $relation['alias'] ?>', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('<?php echo $relation['alias'] ?>', array_values($link));
    }
  }

<?php endforeach; ?>
<?php endif; ?>
}
