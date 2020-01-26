[?php

/**
 * <?php echo $this->getModuleName() ?> actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id$
 */
class <?php echo $this->getGeneratedModuleName() ?>Actions extends <?php echo $this->getActionsBaseClass() ?>

{
<?php include __DIR__.'/../../parts/indexAction.php' ?>

<?php if (isset($this->params['with_show']) && $this->params['with_show']): ?>
<?php include __DIR__.'/../../parts/showAction.php' ?>

<?php endif; ?>
<?php include __DIR__.'/../../parts/newAction.php' ?>

<?php include __DIR__.'/../../parts/createAction.php' ?>

<?php include __DIR__.'/../../parts/editAction.php' ?>

<?php include __DIR__.'/../../parts/updateAction.php' ?>

<?php include __DIR__.'/../../parts/deleteAction.php' ?>

<?php include __DIR__.'/../../parts/processFormAction.php' ?>
}
