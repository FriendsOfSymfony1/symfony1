<?php

$this->installDir(__DIR__.'/sandbox_skeleton');

$this->logSection('install', 'add symfony CLI for Windows users');
$this->getFilesystem()->copy(__DIR__.'/symfony.bat', sfConfig::get('sf_root_dir').'/symfony.bat');

$this->logSection('install', 'add LICENSE');
$this->getFilesystem()->copy(__DIR__.'/../../LICENSE', sfConfig::get('sf_root_dir').'/LICENSE');

$this->logSection('install', 'default to sqlite');
$this->runTask('configure:database', sprintf("'sqlite:%s/sandbox.db'", sfConfig::get('sf_data_dir')));

$this->logSection('install', 'create an application');
$this->runTask('generate:app', 'frontend');

$this->logSection('install', 'publish assets');
$this->runTask('plugin:publish-assets');

$this->logSection('install', 'fix sqlite database permissions');
touch(sfConfig::get('sf_data_dir').'/sandbox.db');
chmod(sfConfig::get('sf_data_dir'), 0777);
chmod(sfConfig::get('sf_data_dir').'/sandbox.db', 0777);

$this->logSection('install', 'add an empty file in empty directories');
$seen = [];
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(sfConfig::get('sf_root_dir')), RecursiveIteratorIterator::CHILD_FIRST) as $path => $item) {
    if (!isset($seen[$path]) && $item->isDir() && !$item->isLink()) {
        touch($item->getRealPath().'/.sf');
    }

    $seen[$item->getPath()] = true;
}
