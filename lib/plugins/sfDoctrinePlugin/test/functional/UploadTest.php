<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'frontend';
require_once dirname(__FILE__).'/../bootstrap/functional.php';

$browser = new sfTestFunctional(new sfBrowser());

$browser
  ->get('/attachment/index')

  ->setField('attachment[file_path]', sfConfig::get('sf_config_dir').'/databases.yml')
  ->click('submit')

  ->with('response')->begin()
    ->checkElement('h1:contains("ok")')
  ->end()
;

$browser->test()->ok(file_exists(sfConfig::get('sf_cache_dir').'/'.AttachmentForm::TEST_GENERATED_FILENAME), 'uploaded file is named correctly');
