[?php

require_once(__DIR__.'/../lib/Base<?php echo ucfirst($this->moduleName) ?>GeneratorConfiguration.class.php');
require_once(__DIR__.'/../lib/Base<?php echo ucfirst($this->moduleName) ?>GeneratorHelper.class.php');

/**
 * <?php echo $this->getModuleName() ?> actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id$
 */
abstract class <?php echo $this->getGeneratedModuleName() ?>Actions extends <?php echo $this->getActionsBaseClass()."\n" ?>
{
  public function preExecute()
  {
    $this->configuration = new <?php echo $this->getModuleName() ?>GeneratorConfiguration();

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($this->getActionName())))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    $this->dispatcher->notify(new sfEvent($this, 'admin.pre_execute', array('configuration' => $this->configuration)));

    $this->helper = new <?php echo $this->getModuleName() ?>GeneratorHelper();

    parent::preExecute();
  }

<?php include __DIR__.'/../../parts/indexAction.php' ?>

<?php if ($this->configuration->hasFilterForm()): ?>
<?php include __DIR__.'/../../parts/filterAction.php' ?>
<?php endif; ?>

<?php include __DIR__.'/../../parts/newAction.php' ?>

<?php include __DIR__.'/../../parts/createAction.php' ?>

<?php include __DIR__.'/../../parts/editAction.php' ?>

<?php include __DIR__.'/../../parts/updateAction.php' ?>

<?php include __DIR__.'/../../parts/deleteAction.php' ?>

<?php if ($this->configuration->getValue('list.batch_actions')): ?>
<?php include __DIR__.'/../../parts/batchAction.php' ?>
<?php endif; ?>

<?php include __DIR__.'/../../parts/processFormAction.php' ?>

<?php if ($this->configuration->hasFilterForm()): ?>
<?php include __DIR__.'/../../parts/filtersAction.php' ?>
<?php endif; ?>

<?php include __DIR__.'/../../parts/paginationAction.php' ?>

<?php include __DIR__.'/../../parts/sortingAction.php' ?>
}
