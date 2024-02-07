<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$_test_dir = realpath(__DIR__.'/..');

require_once $_test_dir.'/../lib/vendor/lime/lime.php';

require_once $_test_dir.'/../lib/util/sfToolkit.class.php';

define('DS', DIRECTORY_SEPARATOR);

class sf_test_project
{
    public $php_cli;
    public $tmp_dir;
    public $t;
    public $current_dir;

    public function initialize($t)
    {
        $this->t = $t;

        $this->tmp_dir = sys_get_temp_dir().DS.'sf_test_project';

        if (is_dir($this->tmp_dir)) {
            $this->clearTmpDir();
            rmdir($this->tmp_dir);
        }

        mkdir($this->tmp_dir, 0777);

        $this->current_dir = getcwd();
        chdir($this->tmp_dir);

        $this->php_cli = \sfToolkit::getPhpCli();
    }

    public function shutdown()
    {
        $this->clearTmpDir();
        rmdir($this->tmp_dir);
        chdir($this->current_dir);
    }

    public function execute_command($cmd, $awaited_return = 0)
    {
        chdir($this->tmp_dir);
        $symfony = file_exists('symfony') ? 'symfony' : __DIR__.'/../../data/bin/symfony';

        ob_start();
        passthru(sprintf('%s "%s" %s 2>&1', $this->php_cli, $symfony, $cmd), $return);
        $content = ob_get_clean();
        $this->t->cmp_ok($return, '==', $awaited_return, sprintf('"symfony %s" returns awaited value (%d)', $cmd, $awaited_return));

        return $content;
    }

    public function get_fixture_content($file)
    {
        return str_replace("\r\n", "\n", file_get_contents(__DIR__.'/fixtures/'.$file));
    }

    protected function clearTmpDir()
    {
        require_once __DIR__.'/../../lib/util/sfToolkit.class.php';
        \sfToolkit::clearDirectory($this->tmp_dir);
    }
}

$plan = 18;
$t = new \lime_test($plan);

if (!extension_loaded('SQLite') && !extension_loaded('pdo_SQLite')) {
    $t->skip('You need SQLite to run these tests', $plan);

    return;
}

$c = new \sf_test_project();
$c->initialize($t);

// generate:*
$content = $c->execute_command('generate:project myproject --orm=Doctrine');
$t->ok(file_exists($c->tmp_dir.DS.'symfony'), '"generate:project" installs the symfony CLI in root project directory');

$content = $c->execute_command('generate:app frontend');
$t->ok(is_dir($c->tmp_dir.DS.'apps'.DS.'frontend'), '"generate:app" creates a "frontend" directory under "apps" directory');
$t->like(file_get_contents($c->tmp_dir.'/apps/frontend/config/settings.yml'), '/escaping_strategy: +true/', '"generate:app" switches escaping_strategy "on" by default');
$t->like(file_get_contents($c->tmp_dir.'/apps/frontend/config/settings.yml'), '/csrf_secret: +\w+/', '"generate:app" generates a csrf_token by default');

$content = $c->execute_command('generate:app backend --escaping-strategy=false --csrf-secret=false');
$t->like(file_get_contents($c->tmp_dir.'/apps/backend/config/settings.yml'), '/escaping_strategy: +false/', '"generate:app" switches escaping_strategy "false"');
$t->like(file_get_contents($c->tmp_dir.'/apps/backend/config/settings.yml'), '/csrf_secret: +false/', '"generate:app" switches csrf_token to "false"');

// failing
$content = $c->execute_command('generate:module wrongapp foo', 1);

$content = $c->execute_command('generate:module frontend foo');
$t->ok(is_dir($c->tmp_dir.DS.'apps'.DS.'frontend'.DS.'modules'.DS.'foo'), '"generate:module" creates a "foo" directory under "modules" directory');

copy(__DIR__.'/fixtures/factories.yml', $c->tmp_dir.DS.'apps'.DS.'frontend'.DS.'config'.DS.'factories.yml');

// test:*
copy(__DIR__.'/fixtures/test/unit/testTest.php', $c->tmp_dir.DS.'test'.DS.'unit'.DS.'testTest.php');

$content = $c->execute_command('test:unit test');
$t->is($content, $c->get_fixture_content('/test/unit/result.txt'), '"test:unit" can launch a particular unit test');

$content = $c->execute_command('test:unit');

// this test is failing on 7.1 because tempnam() raise a notice because it uses sys_get_temp_dir()
// so strict comparison doesn't work because the result contains the notice
// $t->like($content, $c->get_fixture_content('test/unit/result-harness.txt'), '"test:unit" can launch all unit tests');
$t->ok(false !== stripos($content, $c->get_fixture_content('test/unit/result-harness.txt')), '"test:unit" can launch all unit tests');

$content = $c->execute_command('cache:clear');

// Test task autoloading
mkdir($c->tmp_dir.DS.'lib'.DS.'task');
mkdir($pluginDir = $c->tmp_dir.DS.'plugins'.DS.'myFooPlugin'.DS.'lib'.DS.'task', 0777, true);
copy(__DIR__.'/fixtures/task/myPluginTask.class.php', $pluginDir.DS.'myPluginTask.class.php');
file_put_contents(
    $projectConfigurationFile = $c->tmp_dir.DS.'config'.DS.'ProjectConfiguration.class.php',
    str_replace(
        '$this->enablePlugins(\'sfDoctrinePlugin\')',
        '$this->enablePlugins(array(\'sfDoctrinePlugin\', \'myFooPlugin\'))',
        file_get_contents($projectConfigurationFile)
    )
);

$c->execute_command('p:run');

$c->shutdown();
