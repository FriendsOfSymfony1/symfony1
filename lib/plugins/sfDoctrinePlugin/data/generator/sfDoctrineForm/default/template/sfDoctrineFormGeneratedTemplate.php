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
  public function setup()
  {
    $this->setWidgets(array(
<?php foreach ($this->getColumns() as $column): ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new <?php echo $this->getWidgetClassForColumn($column) ?>(<?php echo $this->getWidgetOptionsForColumn($column) ?>),
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>')),
<?php endforeach; ?>
    ));

    $this->setValidators(array(
<?php foreach ($this->getColumns() as $column): ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new <?php echo $this->getValidatorClassForColumn($column) ?>(<?php echo $this->getValidatorOptionsForColumn($column) ?>),
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>', 'required' => false)),
<?php endforeach; ?>
    ));

<?php if ($uniqueColumns = $this->getUniqueColumnNames()): ?>
    $this->validatorSchema->setPostValidator(
<?php if (count($uniqueColumns) > 1): ?>
      new sfValidatorAnd(array(
<?php foreach ($uniqueColumns as $uniqueColumn): ?>
        new sfValidatorDoctrineUnique(array('model' => '<?php echo $this->table->getOption('name') ?>', 'column' => array('<?php echo implode("', '", $uniqueColumn) ?>'))),
<?php endforeach; ?>
      ))
<?php else: ?>
      new sfValidatorDoctrineUnique(array('model' => '<?php echo $this->table->getOption('name') ?>', 'column' => array('<?php echo implode("', '", $uniqueColumns[0]) ?>')))
<?php endif; ?>
    );

<?php endif; ?>
    $this->widgetSchema->setNameFormat('<?php echo $this->underscore($this->modelName) ?>[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
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
