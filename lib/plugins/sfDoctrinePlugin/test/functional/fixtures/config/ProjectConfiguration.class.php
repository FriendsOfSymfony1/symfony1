<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once dirname(__FILE__).'/../../../../../../autoload/sfCoreAutoload.class.php';
\sfCoreAutoload::register();

class ProjectConfiguration extends \sfProjectConfiguration
{
    public function setup()
    {
        $this->enableAllPluginsExcept();

        $this->dispatcher->connect('doctrine.configure', [$this, 'configureDoctrine']);
        $this->dispatcher->connect('doctrine.configure_connection', [$this, 'configureDoctrineConnection']);
        $this->dispatcher->connect('doctrine.filter_model_builder_options', [$this, 'configureDoctrineModelBuilder']);
    }

    public function initializeDoctrine()
    {
        chdir(\sfConfig::get('sf_root_dir'));

        $task = new \sfDoctrineBuildTask($this->dispatcher, new \sfFormatter());
        $task->setConfiguration($this);
        $task->run([], [
            'no-confirmation' => true,
            'db' => true,
            'model' => true,
            'forms' => true,
            'filters' => true,
        ]);
    }

    public function loadFixtures($fixtures)
    {
        $path = \sfConfig::get('sf_data_dir').'/'.$fixtures;
        if (!file_exists($path)) {
            throw new \sfException('Invalid data fixtures file');
        }
        chdir(\sfConfig::get('sf_root_dir'));
        $task = new \sfDoctrineDataLoadTask($this->dispatcher, new \sfFormatter());
        $task->setConfiguration($this);
        $task->run([$path]);
    }

    public function configureDoctrine(\sfEvent $event)
    {
        $manager = $event->getSubject();
        $manager->setAttribute(\Doctrine_Core::ATTR_VALIDATE, true);
    }

    public function configureDoctrineModelBuilder(\sfEvent $event, $options)
    {
        $options['baseClassName'] = 'myDoctrineRecord';

        return $options;
    }

    public function configureDoctrineConnection(\sfEvent $event)
    {
        $parameters = $event->getParameters();

        if ('doctrine2' === $parameters['connection']->getName()) {
            $parameters['connection']->setAttribute(\Doctrine_Core::ATTR_VALIDATE, false);
        }
    }
}
