<?php

require_once dirname(__FILE__).'/../../../../../../autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enableAllPluginsExcept();

    $this->dispatcher->connect('doctrine.configure', array($this, 'configureDoctrineEvent'));
    $this->dispatcher->connect('doctrine.configure_connection', array($this, 'configureDoctrineConnectionEvent'));
  }

  public function initializeDoctrine()
  {
    chdir(sfConfig::get('sf_root_dir'));

    $task = new sfDoctrineBuildTask($this->dispatcher, new sfFormatter());
    $task->setConfiguration($this);
    $task->run(array(), array(
      'no-confirmation' => true,
      'db'              => true,
      'model'           => true,
      'forms'           => true,
      'filters'         => true,
    ));
  }

  public function loadFixtures($fixtures)
  {
    $path = sfConfig::get('sf_data_dir') . '/' . $fixtures;
    if ( ! file_exists($path)) {
      throw new sfException('Invalid data fixtures file');
    }
    chdir(sfConfig::get('sf_root_dir'));
    $task = new sfDoctrineDataLoadTask($this->dispatcher, new sfFormatter());
    $task->setConfiguration($this);
    $task->run(array($path));
  }

  public function configureDoctrineEvent(sfEvent $event)
  {
    $manager = $event->getSubject();
    $manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, true);

    $options = array('baseClassName' => 'myDoctrineRecord');
    sfConfig::set('doctrine_model_builder_options', $options);
  }

  public function configureDoctrineConnectionEvent(sfEvent $event)
  {
    $parameters = $event->getParameters();

    if ('doctrine2' === $parameters['connection']->getName())
    {
      $parameters['connection']->setAttribute(Doctrine_Core::ATTR_VALIDATE, false);
    }
  }
}
