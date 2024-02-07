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
 * Finds deprecated usage in a project.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidateTask extends \sfBaseTask
{
    /**
     * @see \sfTask
     */
    protected function configure()
    {
        $this->namespace = 'project';
        $this->name = 'validate';
        $this->briefDescription = 'Finds deprecated usage in a project';

        $this->detailedDescription = <<<'EOF'
The [project:validate|INFO] task detects deprecated usage in your project.

  [./symfony project:validate|INFO]

The task lists all the files you need to change before switching to
symfony 1.4.
EOF;
    }

    /**
     * @see \sfTask
     */
    protected function execute($arguments = [], $options = [])
    {
        foreach ($this->getUpgradeClasses() as $i => $class) {
            $v = new $class($this->dispatcher, $this->formatter);

            $this->logBlock(($i + 1).'. '.$v->getHeader(), 'QUESTION_LARGE');

            $v->setCommandApplication($this->commandApplication);
            $v->setConfiguration($this->configuration);
            $files = $v->validate();

            if (!$files) {
                $this->log('  '.$this->formatter->format(' OK ', 'INFO'));

                continue;
            }

            $this->log('  '.$this->formatter->format(' '.count($files).' file(s) need to be changed. ', 'ERROR'));

            foreach ($files as $file => $value) {
                $this->log('  '.$this->formatter->format($this->formatFile($file), 'INFO'));

                if (true !== $value) {
                    $this->log('    '.$value);
                }
            }

            $this->log($v->getExplanation());
        }
    }

    protected function formatFile($file)
    {
        return str_replace(realpath(\sfConfig::get('sf_root_dir')), 'ROOT', realpath($file));
    }

    protected function getUpgradeClasses()
    {
        $baseDir = __DIR__.'/validation/';
        $classes = [];

        foreach (glob($baseDir.'*.class.php') as $file) {
            $class = str_replace([$baseDir, '.class.php'], '', $file);

            if ('sfValidation' != $class) {
                $classes[] = $class;

                require_once $baseDir.$class.'.class.php';
            }
        }

        return $classes;
    }
}
