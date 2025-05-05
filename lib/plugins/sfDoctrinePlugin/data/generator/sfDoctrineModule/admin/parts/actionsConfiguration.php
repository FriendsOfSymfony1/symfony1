public function getActionsDefault()
  {
    return <?php echo $this->asPhp($this->config['actions'] ?? array()) ?>;
<?php unset($this->config['actions']) ?>
  }

  public function getFormActions()
  {
    return <?php echo $this->asPhp($this->config['form']['actions'] ?? array('_delete' => null, '_list' => null, '_save' => null, '_save_and_add' => null)) ?>;
<?php unset($this->config['form']['actions']) ?>
  }

  public function getNewActions()
  {
    return <?php echo $this->asPhp($this->config['new']['actions'] ?? array()) ?>;
<?php unset($this->config['new']['actions']) ?>
  }

  public function getEditActions()
  {
    return <?php echo $this->asPhp($this->config['edit']['actions'] ?? array()) ?>;
<?php unset($this->config['edit']['actions']) ?>
  }

  public function getListObjectActions()
  {
    return <?php echo $this->asPhp($this->config['list']['object_actions'] ?? array('_edit' => null, '_delete' => null)) ?>;
<?php unset($this->config['list']['object_actions']) ?>
  }

  public function getListActions()
  {
    return <?php echo $this->asPhp($this->config['list']['actions'] ?? array('_new' => null)) ?>;
<?php unset($this->config['list']['actions']) ?>
  }

  public function getListBatchActions()
  {
    return <?php echo $this->asPhp($this->config['list']['batch_actions'] ?? array('_delete' => null)) ?>;
<?php unset($this->config['list']['batch_actions']) ?>
  }
