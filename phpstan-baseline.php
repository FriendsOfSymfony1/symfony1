<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfData\\:\\:\\$maps\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/addon/sfData.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$position might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/autoload/sfAutoloadAgain.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfAPCCache\\:\\:clean\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfAPCCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfAPCCache\\:\\:removePattern\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfAPCCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfAPCuCache\\:\\:clean\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfAPCuCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfAPCuCache\\:\\:removePattern\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfAPCuCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function eaccelerator_gc not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfEAcceleratorCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function eaccelerator_get not found\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/cache/sfEAcceleratorCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function eaccelerator_list_keys not found\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/cache/sfEAcceleratorCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function eaccelerator_put not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfEAcceleratorCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function eaccelerator_rm not found\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/cache/sfEAcceleratorCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfEAcceleratorCache\\:\\:removePattern\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfEAcceleratorCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$key$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfEAcceleratorCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfFileCache\\:\\:removePattern\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfFileCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfMemcacheCache\\:\\:clean\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfMemcacheCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfMemcacheCache\\:\\:removePattern\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfMemcacheCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Constant XC_TYPE_VAR not found\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/lib/cache/sfXCacheCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfXCacheCache\\:\\:removePattern\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfXCacheCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Result of function xcache_clear_cache \\(void\\) is used\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/cache/sfXCacheCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfApplicationConfiguration\\:\\:getDecoratorDir\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/config/sfApplicationConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$included might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/config/sfApplicationConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class class not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/config/sfConfigCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$timer might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/config/sfConfigCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$directory might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/config/sfPluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$names might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/config/sfPluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$viewName might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/config/sfViewConfigHandler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_content might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/controller/default/templates/defaultLayout.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_params might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/controller/default/templates/moduleSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_close not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/database/sfMySQLDatabase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_query not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/database/sfMySQLDatabase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_select_db not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/database/sfMySQLDatabase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanel\\:\\:getTitleUrl\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/debug/sfWebDebugPanel.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelCache\\:\\:getPanelContent\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/debug/sfWebDebugPanelCache.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelMailer\\:\\:getTitle\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/debug/sfWebDebugPanelMailer.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelMemory\\:\\:getPanelContent\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/debug/sfWebDebugPanelMemory.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelMemory\\:\\:getPanelTitle\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/debug/sfWebDebugPanelMemory.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelSymfonyVersion\\:\\:getPanelContent\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/debug/sfWebDebugPanelSymfonyVersion.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelSymfonyVersion\\:\\:getPanelTitle\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/debug/sfWebDebugPanelSymfonyVersion.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelTimer\\:\\:getPanelContent\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/debug/sfWebDebugPanelTimer.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelView\\:\\:getTitle\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/debug/sfWebDebugPanelView.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfException\\:\\:fileExcerpt\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/exception/sfException.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function get_javascripts invoked with 1 parameter, 0 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/filter/sfCommonFilter.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function get_stylesheets invoked with 1 parameter, 0 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/filter/sfCommonFilter.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfExecutionFilter\\:\\:executeView\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/filter/sfExecutionFilter.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfModelGeneratorConfiguration\\:\\:getPagerClass\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/generator/sfModelGeneratorConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfModelGeneratorConfiguration\\:\\:getPagerMaxPerPage\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/generator/sfModelGeneratorConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$config$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/generator/sfModelGeneratorConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$config in isset\\(\\) is never defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/generator/sfModelGeneratorConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function include_http_metas\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/helper/AssetHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Function include_metas\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/helper/AssetHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Function include_title\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/helper/AssetHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Function get_component\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/helper/PartialHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Function get_component_slot\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/helper/PartialHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$timer might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/helper/PartialHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$pattern might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/helper/TextHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$replacement might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/helper/TextHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$result$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/i18n/sfChoiceFormat.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfCultureInfo\\:\\:getCultures\\(\\) should return array but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/i18n/sfCultureInfo.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfMessageSource_File\\:\\:\\$dataExt\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_File.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Constant MYSQL_NUM not found\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_MySQL.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_affected_rows not found\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_MySQL.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_close not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_MySQL.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_fetch_array not found\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_MySQL.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_num_rows not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_MySQL.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_query not found\\.$#',
	'count' => 10,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_MySQL.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_real_escape_string not found\\.$#',
	'count' => 9,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_MySQL.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_result not found\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_MySQL.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_select_db not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_MySQL.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cat_id might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_SQLite3.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot unset offset \'strings\' on array\\{meta\\: array\\{PO\\-Revision\\-Date\\: non\\-falsy\\-string\\}\\}\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/i18n/sfMessageSource_gettext.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfNumberFormat\\:\\:setPattern\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/i18n/sfNumberFormat.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$suffix might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/i18n/sfNumberFormat.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Constructor of class sfNoMailer has an unused parameter \\$dispatcher\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/mailer/sfNoMailer.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Constructor of class sfNoMailer has an unused parameter \\$options\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/mailer/sfNoMailer.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfPearRest11\\:\\:\\$_rest\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugin/sfPearRest11.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfPearRestPlugin\\:\\:\\$_rest\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugin/sfPearRestPlugin.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfPearRestPlugin\\:\\:betterStates\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugin/sfPearRestPlugin.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfPearRestPlugin\\:\\:getDownloadURL\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugin/sfPearRestPlugin.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfPearRestPlugin\\:\\:packageInfo\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugin/sfPearRestPlugin.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$download might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugin/sfPluginManager.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to sprintf contains 0 placeholders, 1 value given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugin/sfSymfonyPluginManager.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$this might not be defined\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/config/installer.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_AUTOLOAD_TABLE_CLASSES on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/config/sfDoctrinePluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_AUTO_ACCESSOR_OVERRIDE on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/config/sfDoctrinePluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_EXPORT on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/config/sfDoctrinePluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_RECURSIVE_MERGE_FIXTURES on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/config/sfDoctrinePluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_VALIDATE on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/config/sfDoctrinePluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant EXPORT_ALL on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/config/sfDoctrinePluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant VALIDATE_NONE on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/config/sfDoctrinePluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getInstance\\(\\) on an unknown class Doctrine_Manager\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/config/sfDoctrinePluginConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineCli\\:\\:notify\\(\\) should return false but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/cli/sfDoctrineCli.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$event of method sfDoctrineConnectionListener\\:\\:postConnect\\(\\) has invalid type Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionListener.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant CONN_EXEC on an unknown class Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant CONN_QUERY on an unknown class Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant STMT_EXECUTE on an unknown class Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfDoctrineConnectionProfiler\\:\\:__call\\(\\)\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$event of method sfDoctrineConnectionProfiler\\:\\:postExec\\(\\) has invalid type Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$event of method sfDoctrineConnectionProfiler\\:\\:postQuery\\(\\) has invalid type Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$event of method sfDoctrineConnectionProfiler\\:\\:postStmtExecute\\(\\) has invalid type Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$event of method sfDoctrineConnectionProfiler\\:\\:preExec\\(\\) has invalid type Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$event of method sfDoctrineConnectionProfiler\\:\\:preQuery\\(\\) has invalid type Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$event of method sfDoctrineConnectionProfiler\\:\\:preStmtExecute\\(\\) has invalid type Doctrine_Event\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineConnectionProfiler.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getInstance\\(\\) on an unknown class Doctrine_Manager\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineDatabase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$stringName might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineDatabase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant VERSION on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/debug/sfWebDebugPanelDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Connection not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/debug/sfWebDebugPanelDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelDoctrine\\:\\:getTitle\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/debug/sfWebDebugPanelDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ONE on an unknown class Doctrine_Relation\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getInstance\\(\\) on an unknown class Doctrine_Manager\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormFilterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Query not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormFilterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$query of method sfFormFilterDoctrine\\:\\:addBooleanQuery\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormFilterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$query of method sfFormFilterDoctrine\\:\\:addDateQuery\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormFilterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$query of method sfFormFilterDoctrine\\:\\:addEnumQuery\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormFilterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$query of method sfFormFilterDoctrine\\:\\:addForeignKeyQuery\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormFilterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$query of method sfFormFilterDoctrine\\:\\:addNumberQuery\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormFilterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$query of method sfFormFilterDoctrine\\:\\:addTextQuery\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/form/sfFormFilterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineColumn.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$table of method sfDoctrineColumn\\:\\:__construct\\(\\) has invalid type Doctrine_Table\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineColumn.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$table of method sfDoctrineColumn\\:\\:setTable\\(\\) has invalid type Doctrine_Table\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineColumn.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormFilterGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineFormFilterGenerator\\:\\:generate\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormFilterGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$name might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormFilterGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$pluginName might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormFilterGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant MANY on an unknown class Doctrine_Relation\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ONE on an unknown class Doctrine_Relation\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfDoctrineFormGenerator\\:\\:isColumnNotNull\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method filterInvalidModels\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getLoadedModels\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 6,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method initializeModels\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method loadModels\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineFormGenerator\\:\\:generate\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$pluginName might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineFormGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant MANY on an unknown class Doctrine_Relation\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$columns might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineGenerator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/mailer/Swift_DoctrineSpool.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Record not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/mailer/Swift_DoctrineSpool.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Swift_DoctrineSpool\\:\\:queueMessage\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/mailer/Swift_DoctrineSpool.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$message of method Swift_DoctrineSpool\\:\\:queueMessage\\(\\) has invalid type Swift_Mime_Message\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/mailer/Swift_DoctrineSpool.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/pager/sfDoctrinePager.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Collection not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/pager/sfDoctrinePager.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfDoctrineRecord\\:\\:exists\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfDoctrineRecord\\:\\:get\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfDoctrineRecord\\:\\:getTable\\(\\)\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfDoctrineRecord\\:\\:identifier\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfDoctrineRecord\\:\\:set\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Caught class Doctrine_Record_UnknownPropertyException not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$e2 in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord.class.php',
];
$ignoreErrors[] = [
	'message' => '#^sfDoctrineRecord\\:\\:__call\\(\\) calls parent\\:\\:__call\\(\\) but sfDoctrineRecord does not extend any class\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$record of method sfDoctrineRecordI18nFilter\\:\\:filterGet\\(\\) has invalid type Doctrine_Record\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecordI18nFilter.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$record of method sfDoctrineRecordI18nFilter\\:\\:filterSet\\(\\) has invalid type Doctrine_Record\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecordI18nFilter.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/routing/sfDoctrineRoute.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Collection not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/routing/sfDoctrineRoute.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Record not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/routing/sfDoctrineRoute.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class Doctrine_Collection not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/routing/sfDoctrineRoute.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$query of method sfDoctrineRoute\\:\\:setListQuery\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/routing/sfDoctrineRoute.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getGlobalDefinitionKeys\\(\\) on an unknown class Doctrine_Import_Schema\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBaseTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class sfDoctrineCli does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBaseTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineBuildDbTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildDbTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineBuildFiltersTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFiltersTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineBuildFormsTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildFormsTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class Doctrine_Import_Schema not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildModelTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineBuildModelTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildModelTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$match might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildModelTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineBuildSchemaTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildSchemaTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineBuildSqlTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildSqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineBuildTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineBuildTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getLoadedModels\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCleanModelFilesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method loadModels\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCleanModelFilesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineCleanModelFilesTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCleanModelFilesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method compile\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCompileTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineCompileTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCompileTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineConfigureDatabaseTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineConfigureDatabaseTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method createTablesFromArray\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCreateModelTablesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getInstance\\(\\) on an unknown class Doctrine_Manager\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCreateModelTablesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCreateModelTablesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineCreateModelTables\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCreateModelTablesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineDataDumpTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineDataDumpTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineDataLoadTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineDataLoadTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineDeleteModelFilesTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineDeleteModelFilesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineDeleteModelFilesTask\\:\\:valuesToRegex\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineDeleteModelFilesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant HYDRATE_SCALAR on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineDqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method create\\(\\) on an unknown class Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineDqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineDqlTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineDqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineDropDbTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineDropDbTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineGenerateAdminTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineGenerateMigrationTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineGenerateMigrationTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineGenerateMigrationsDbTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineGenerateMigrationsDbTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineGenerateMigrationsDiffTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineGenerateMigrationsDiffTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineGenerateMigrationsModelsTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineGenerateMigrationsModelsTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfDoctrineGenerateModuleTask\\:\\:\\$constants\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineGenerateModuleTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineGenerateModuleTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineGenerateModuleTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant MODEL_LOADING_CONSERVATIVE on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineInsertSqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method createTablesFromArray\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineInsertSqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getLoadedModels\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineInsertSqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method loadModels\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineInsertSqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineInsertSqlTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineInsertSqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class Doctrine_Migration not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineMigrateTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineMigrateTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineMigrateTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/test/sfTesterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Connection not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/test/sfTesterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class LogicConnection not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/test/sfTesterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$match might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/test/sfTesterDoctrine.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/validator/sfValidatorDoctrineChoice.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/validator/sfValidatorDoctrineUnique.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$object of method sfValidatorDoctrineUnique\\:\\:isUpdate\\(\\) has invalid type Doctrine_Record\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/validator/sfValidatorDoctrineUnique.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/widget/sfWidgetFormDoctrineArrayChoice.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/widget/sfWidgetFormDoctrineChoice.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Collection not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/widget/sfWidgetFormDoctrineChoice.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Query not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/widget/sfWidgetFormDoctrineChoice.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class Doctrine_Record not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/widget/sfWidgetFormDoctrineChoice.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class Doctrine_Collection not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/widget/sfWidgetFormDoctrineChoice.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$app might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/bootstrap/functional.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method AdminGenBrowser\\:\\:getContext\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/AdminGenBrowser.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/AdminGenBrowser.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_EXPORT on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/EnvironmentSetupTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_VALIDATE on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/EnvironmentSetupTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant EXPORT_TABLES on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/EnvironmentSetupTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method create\\(\\) on an unknown class Doctrine_Query\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/EnvironmentSetupTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getInstance\\(\\) on an unknown class Doctrine_Manager\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/EnvironmentSetupTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method UserGroupForm\\:\\:useFields\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/FormTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method create\\(\\) on an unknown class Doctrine_Query\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/FormTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class ProfileForm does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/FormTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class UserForm does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/FormTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property MyArticleForm\\:\\:\\$object\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/I18nTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method MyArticleForm\\:\\:embedForm\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/I18nTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method MyArticleForm\\:\\:embedI18n\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/I18nTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class AuthorForm does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/I18nTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class MyArticleForm does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/I18nTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant HYDRATE_ARRAY on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/PagerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method create\\(\\) on an unknown class Doctrine_Query\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/PagerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/RouteTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/SchemaMergeTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/Ticket/5269Test.php',
];
$ignoreErrors[] = [
	'message' => '#^Class TestUserForm does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/Ticket/5269Test.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_VALIDATE on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/config/ProjectConfiguration.class.php',
];
$ignoreErrors[] = [
	'message' => '#^BlogArticleFormFilter\\:\\:configure\\(\\) calls parent\\:\\:configure\\(\\) but BlogArticleFormFilter does not extend any class\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/filter/doctrine/BlogArticleFormFilter.class.php',
];
$ignoreErrors[] = [
	'message' => '#^BlogAuthorFormFilter\\:\\:configure\\(\\) calls parent\\:\\:configure\\(\\) but BlogAuthorFormFilter does not extend any class\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/filter/doctrine/BlogAuthorFormFilter.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method ArticleForm\\:\\:embedI18n\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/ArticleForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property AttachmentForm\\:\\:\\$validatorSchema\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/AttachmentForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property AttachmentForm\\:\\:\\$widgetSchema\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/AttachmentForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method AttachmentForm\\:\\:getObject\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/AttachmentForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method AttachmentForm\\:\\:isNew\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/AttachmentForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^AuthorInheritanceConcreteForm\\:\\:configure\\(\\) calls parent\\:\\:configure\\(\\) but AuthorInheritanceConcreteForm does not extend any class\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/AuthorInheritanceConcreteForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^BlogArticleForm\\:\\:configure\\(\\) calls parent\\:\\:configure\\(\\) but BlogArticleForm does not extend any class\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/BlogArticleForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^BlogAuthorForm\\:\\:configure\\(\\) calls parent\\:\\:configure\\(\\) but BlogAuthorForm does not extend any class\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/BlogAuthorForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property UserForm\\:\\:\\$object\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/UserForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method UserForm\\:\\:embedForm\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/UserForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class ProfileForm does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/form/doctrine/UserForm.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property Article\\:\\:\\$slug\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/Article.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method ArticleTable\\:\\:createQuery\\(\\)\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/ArticleTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method create\\(\\) on an unknown class Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/ArticleTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$q of method ArticleTable\\:\\:addOnHomepage\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/ArticleTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$q of method ArticleTable\\:\\:routeTest10\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/ArticleTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$q of method ArticleTable\\:\\:testAdminGenTableMethod\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/ArticleTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$query of method ArticleTable\\:\\:retrieveArticle1\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/ArticleTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method Author\\:\\:_set\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/Author.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method Author\\:\\:assignIdentifier\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/Author.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method Author\\:\\:exists\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/Author.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/Author.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method AuthorTable\\:\\:createQuery\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/AuthorTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$q of method AuthorTable\\:\\:testTableMethod2\\(\\) has invalid type Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/AuthorTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/SettingsPlugin/SettingTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method User\\:\\:_set\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/User.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method UserTable\\:\\:createQuery\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/lib/model/doctrine/UserTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/fixtures/plugins/SettingsPlugin/lib/model/doctrine/PluginSettingTable.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/functional/sfDoctrineRecordTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property NumericFieldForm\\:\\:\\$validatorSchema\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property NumericFieldForm\\:\\:\\$widgetSchema\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method DefaultValuesForm\\:\\:setDefault\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method NumericFieldForm\\:\\:setDefault\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class ArticleForm does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class AuthorForm does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class DefaultValuesForm does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method TestFormFilter\\:\\:setValidators\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormFilterDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method TestFormFilter\\:\\:setWidgets\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormFilterDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method ArticleFormFilter\\:\\:getFields\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormFilterDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method create\\(\\) on an unknown class Doctrine_Query\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormFilterDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class ArticleFormFilter does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/form/sfFormFilterDoctrineTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant HYDRATE_NONE on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/pager/sfDoctrinePagerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/pager/sfDoctrinePagerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getInstance\\(\\) on an unknown class Doctrine_Manager\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/record/sfDoctrineRecordTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/record/sfDoctrineRecordTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method Test\\:\\:hasColumn\\(\\)\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineColumnTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method Test\\:\\:hasMany\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineColumnTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method TestRelation\\:\\:hasColumn\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineColumnTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method TestRelation\\:\\:hasOne\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineColumnTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method connection\\(\\) on an unknown class Doctrine_Manager\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineColumnTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 5,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineColumnTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Instantiated class Doctrine_Adapter_Mock not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineColumnTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_TBLNAME_FORMAT on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineDatabaseTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_USE_NATIVE_ENUM on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineDatabaseTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant ATTR_VALIDATE on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineDatabaseTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to constant VALIDATE_ALL on an unknown class Doctrine_Core\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/sfDoctrineDatabaseTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/validator/sfValidatorDoctrineChoiceTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getTable\\(\\) on an unknown class Doctrine_Core\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/test/unit/widget/sfWidgetFormDoctrineChoiceTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfPatternRouting\\:\\:connect\\(\\) should return array but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/routing/sfPatternRouting.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfPatternRouting\\:\\:setRoutes\\(\\) should return array but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/routing/sfPatternRouting.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfServiceContainerDumperGraphviz\\:\\:\\$options\\.$#',
	'count' => 8,
	'path' => __DIR__ . '/lib/service/sfServiceContainerDumperGraphviz.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfServiceContainerDumperPhp\\:\\:addServiceShared\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/service/sfServiceContainerDumperPhp.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method getConnection\\(\\) on an unknown class Propel\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/storage/sfDatabaseSessionStorage.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_error not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/storage/sfMySQLSessionStorage.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_fetch_row not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/storage/sfMySQLSessionStorage.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_num_rows not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/storage/sfMySQLSessionStorage.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_query not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/storage/sfMySQLSessionStorage.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_real_escape_string not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/storage/sfMySQLSessionStorage.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfAppRoutesTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/app/sfAppRoutesTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfCacheClearTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/cache/sfCacheClearTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfConfigureAuthorTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/configure/sfConfigureAuthorTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfGenerateAppTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/generator/sfGenerateAppTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfGenerateModuleTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/generator/sfGenerateModuleTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfGenerateProjectTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/generator/sfGenerateProjectTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfGenerateTaskTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/generator/sfGenerateTaskTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfHelpTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/help/sfHelpTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfListTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/help/sfListTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$namespacesXML might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/help/sfListTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfI18nExtractTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/i18n/sfI18nExtractTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfI18nFindTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/i18n/sfI18nFindTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfLogClearTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/log/sfLogClearTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfLogRotateTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/log/sfLogRotateTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfPluginAddChannelTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/plugin/sfPluginAddChannelTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfPluginInstallTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/plugin/sfPluginInstallTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfPluginListTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/plugin/sfPluginListTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfPluginPublishAssetsTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/plugin/sfPluginPublishAssetsTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfPluginUninstallTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/plugin/sfPluginUninstallTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfPluginUpgradeTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/plugin/sfPluginUpgradeTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfProjectClearControllersTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/project/sfProjectClearControllersTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfProjectDeployTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/project/sfProjectDeployTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfProjectDisableTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/project/sfProjectDisableTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfProjectEnableTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/project/sfProjectEnableTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfProjectOptimizeTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/project/sfProjectOptimizeTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfProjectPermissionsTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/project/sfProjectPermissionsTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfProjectSendEmailsTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/project/sfProjectSendEmailsTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfValidateTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/project/sfProjectValidateTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfBaseTask\\:\\:checkAppExists\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/sfBaseTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfBaseTask\\:\\:checkModuleExists\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/sfBaseTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfBaseTask\\:\\:checkProjectExists\\(\\) should return true but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/sfBaseTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfTestCoverageTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/test/sfTestCoverageTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfTestFunctionalTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/test/sfTestFunctionalTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfTestPluginTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/test/sfTestPluginTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfTestUnitTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/task/test/sfTestUnitTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfTestFunctionalBase\\:\\:doClick\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/test/sfTestFunctionalBase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfTestFunctionalBase\\:\\:doClickCssSelector\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/test/sfTestFunctionalBase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfTestFunctionalBase\\:\\:doClickElement\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/test/sfTestFunctionalBase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method sfTestFunctionalBase\\:\\:resetCurrentException\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/test/sfTestFunctionalBase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfContext\\:\\:getActionName\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/util/sfContext.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfContext\\:\\:getModuleDirectory\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/util/sfContext.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfContext\\:\\:getModuleName\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/util/sfContext.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$timer might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/util/sfContext.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$cleanTime might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/validator/sfValidatorDate.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfValidatorDecorator\\:\\:setMessage\\(\\) should return sfValidatorBase but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/validator/sfValidatorDecorator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfValidatorDecorator\\:\\:setOption\\(\\) should return sfValidatorBase but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/validator/sfValidatorDecorator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfValidatorDecorator\\:\\:setOptions\\(\\) should return sfValidatorBase but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/validator/sfValidatorDecorator.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfValidatorFDTokenOperator\\:\\:\\$arguments\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/lib/validator/sfValidatorFromDescription.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot unset offset \'left_field\' on string\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/validator/sfValidatorSchemaCompare.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot unset offset \'operator\' on string\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/validator/sfValidatorSchemaCompare.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot unset offset \'right_field\' on string\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/validator/sfValidatorSchemaCompare.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to sprintf contains 6 placeholders, 5 values given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/vendor/lime/lime.php',
];
$ignoreErrors[] = [
	'message' => '#^Offset \'output\' on array\\{\\} in isset\\(\\) does not exist\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/vendor/lime/lime.php',
];
$ignoreErrors[] = [
	'message' => '#^Undefined variable\\: \\$result$#',
	'count' => 2,
	'path' => __DIR__ . '/lib/vendor/lime/lime.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$output might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/vendor/lime/lime.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$timer might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/view/sfPartialView.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfViewCacheManager\\:\\:remove\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/view/sfViewCacheManager.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfViewParameterHolder\\:\\:initialize\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/view/sfViewParameterHolder.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWidgetFormChoice\\:\\:setIdFormat\\(\\) should return sfWidget but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/widget/sfWidgetFormChoice.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot call method getFormFormatter\\(\\) on array\\<sfWidgetForm\\>\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/widget/sfWidgetFormSchema.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$app might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/bootstrap/functional.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method myTestBrowser\\:\\:getResponse\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/cacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property cacheActions\\:\\:\\$image\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property cacheActions\\:\\:\\$page\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property cacheComponents\\:\\:\\$componentParam\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/actions/components.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property cacheComponents\\:\\:\\$requestParam\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/actions/components.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_params might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/templates/_anotherCacheablePartial.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_params might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/templates/_cacheablePartial.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_response might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/templates/_contextualCacheableComponent.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_params might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/templates/_contextualCacheablePartial.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_response might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/templates/_contextualCacheablePartial.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$image might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/templates/imageSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$page might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/modules/cache/templates/listSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_content might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/templates/image.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_content might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/cache/templates/layout.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property authActions\\:\\:\\$auth_password\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/auth/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property authActions\\:\\:\\$auth_user\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/auth/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property authActions\\:\\:\\$msg\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/auth/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$auth_password might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/auth/templates/basicSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$auth_user might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/auth/templates/basicSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$msg might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/auth/templates/basicSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property autoloadActions\\:\\:\\$lib1\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property autoloadActions\\:\\:\\$lib2\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property autoloadActions\\:\\:\\$lib3\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property autoloadActions\\:\\:\\$lib4\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property autoloadActions\\:\\:\\$o\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$lib1 might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$lib2 might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$lib3 might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$lib4 might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$o might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/autoload/templates/myAutoloadSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_params might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/configFiltersSimpleFilter/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property escapingActions\\:\\:\\$var\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/escaping/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$arr might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/escaping/templates/_partial1.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_data might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/escaping/templates/_partial1.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$var might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/escaping/templates/_partial1.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_data might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/escaping/templates/_partial2.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$var might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/escaping/templates/_partial2.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/escaping/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$var might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/escaping/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property presentationActions\\:\\:\\$foo\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/presentation/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$foo might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/presentation/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_context might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/modules/presentation/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_content might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/templates/layout.iphone.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_content might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/templates/layout.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_content might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/frontend/templates/layout.xml.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property i18nActions\\:\\:\\$form\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property i18nActions\\:\\:\\$localTest\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property i18nActions\\:\\:\\$otherLocalTest\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property i18nActions\\:\\:\\$otherTest\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property i18nActions\\:\\:\\$test\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$form might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/templates/i18nFormSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$localTest might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$otherLocalTest might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$otherTest might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$test might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/modules/i18n/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$sf_content might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/apps/i18n/templates/layout.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property autoloadPluginActions\\:\\:\\$lib1\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfAutoloadPlugin/modules/autoloadPlugin/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property autoloadPluginActions\\:\\:\\$lib2\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfAutoloadPlugin/modules/autoloadPlugin/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property autoloadPluginActions\\:\\:\\$lib3\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfAutoloadPlugin/modules/autoloadPlugin/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$lib1 might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfAutoloadPlugin/modules/autoloadPlugin/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$lib2 might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfAutoloadPlugin/modules/autoloadPlugin/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$lib3 might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfAutoloadPlugin/modules/autoloadPlugin/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfI18NPluginActions\\:\\:\\$localTest\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfI18NPluginActions\\:\\:\\$otherTest\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfI18NPluginActions\\:\\:\\$test\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfI18NPluginActions\\:\\:\\$testForPluginI18N\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfI18NPluginActions\\:\\:\\$yetAnotherTest\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/actions/actions.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$localTest might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$otherTest might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$test might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$testForPluginI18N might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$yetAnotherTest might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/functional/fixtures/plugins/sfI18NPlugin/modules/sfI18NPlugin/templates/indexSuccess.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myPluginTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/other/fixtures/task/myPluginTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/test/unit/action/sfComponentTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:clean\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:get\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:getLastModified\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:getTimeout\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:has\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:remove\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:removePattern\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:set\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfSimpleCache\\:\\:clean\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfFunctionCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfSimpleCache\\:\\:remove\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfFunctionCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfSimpleCache\\:\\:removePattern\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfFunctionCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfSimpleCache\\:\\:set\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/cache/sfFunctionCacheTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$this might not be defined\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/test/unit/config/fixtures/sfFilterConfigHandler/result.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myConfigHandler\\:\\:execute\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/config/sfConfigHandlerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myConfigHandler\\:\\:execute\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/config/sfYamlConfigHandlerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class myController does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/controller/sfControllerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/unit/controller/sfControllerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class sfFrontWebController constructor invoked with 2 parameters, 1 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/controller/sfWebControllerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/unit/controller/sfWebControllerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/unit/database/sfDatabaseTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/unit/filter/sfFilterTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$values might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/form/sfFormTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myGenerator\\:\\:generate\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/generator/sfGeneratorTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/helper/AssetHelperTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/helper/DateHelperTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method MyTestPartialView\\:\\:initialize\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/helper/PartialHelperTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/helper/PartialHelperTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Function fix_double_escape invoked with 3 parameters, 1 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/helper/TagHelperTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/helper/TagHelperTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/helper/UrlHelperTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Constructor of class sfMessageSource_Simple has an unused parameter \\$source\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/i18n/sfMessageSourceTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfMessageSource_Simple\\:\\:catalogues\\(\\) should return array but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/i18n/sfMessageSourceTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfMessageSource_Simple\\:\\:delete\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/i18n/sfMessageSourceTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfMessageSource_Simple\\:\\:save\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/i18n/sfMessageSourceTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfMessageSource_Simple\\:\\:update\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/i18n/sfMessageSourceTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfMessageSource_Simple\\:\\:delete\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/i18n/sfMessageSource_FileTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfMessageSource_Simple\\:\\:save\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/i18n/sfMessageSource_FileTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfMessageSource_Simple\\:\\:update\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/i18n/sfMessageSource_FileTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class myLogger does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/log/sfLoggerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$message of method TestMailMessage\\:\\:setMessage\\(\\) has invalid type Swift_Mime_Message\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/mailer/fixtures/TestMailMessage.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$message of method TestMailerTransport\\:\\:send\\(\\) has invalid type Swift_Mime_Message\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/mailer/fixtures/TestMailerTransport.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method TestSpool\\:\\:flushQueue\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/mailer/fixtures/TestSpool.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\$message of method TestSpool\\:\\:queueMessage\\(\\) has invalid type Swift_Mime_Message\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/mailer/fixtures/TestSpool.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method PEAR\\:\\:raiseError\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/plugin/sfPearDownloaderTest.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method PEAR\\:\\:raiseError\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/plugin/sfPearRestTest.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Constant SF_PLUGIN_TEST_DIR not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/plugin/sfPluginTestHelper.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Class myRequest does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/test/unit/request/sfRequestTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/unit/request/sfRequestTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class myRequest does not have a constructor and must be instantiated without any parameters\\.$#',
	'count' => 34,
	'path' => __DIR__ . '/test/unit/request/sfWebRequestTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myResponse\\:\\:__serialize\\(\\) should return array but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/response/sfResponseTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myResponse\\:\\:serialize\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/response/sfResponseTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/response/sfResponseTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$this might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/routing/fixtures/config_routing.yml.php',
];
$ignoreErrors[] = [
	'message' => '#^Array has 2 duplicate keys with value \'action\' \\(\'action\', \'action\'\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/unit/routing/sfPatternRoutingTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method MyRoute\\:\\:tokenizeBufferBefore\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/routing/sfRouteTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property sfContext\\:\\:\\$dispatcher\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/test/unit/sfContextMock.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_fetch_row not found\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_free_result not found\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_num_rows not found\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_query not found\\.$#',
	'count' => 7,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Function mysql_select_db not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$mysql_config in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$retrieved_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$write might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$mysqli_config in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLiStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$retrieved_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLiStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$write might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfMySQLiStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$retrieved_data might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfPDOSessionStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$write might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfPDOSessionStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myStorage\\:\\:regenerate\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/storage/sfStorageTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method ApplicationTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/task/sfBaseTaskTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method TestTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/task/sfBaseTaskTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method BaseTestTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/task/sfTaskTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method mockTestFunctional\\:\\:call\\(\\) should return sfTestFunctionalBase but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/test/sfTestFunctionalTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/unit/user/sfUserTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class frontend_prodServiceContainer not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/util/sfContextTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Class frontend_testServiceContainer not found\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/util/sfContextTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/util/sfFinderTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/test/unit/util/sfInflectorTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$e might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/validator/sfValidatorBaseTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$serialized might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/validator/sfValidatorErrorSchemaTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$serialized might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/validator/sfValidatorErrorTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$evaledValidator might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/validator/sfValidatorFromDescriptionTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:clean\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/view/sfViewCacheManagerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:remove\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/view/sfViewCacheManagerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:removePattern\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/view/sfViewCacheManagerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myCache\\:\\:set\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/view/sfViewCacheManagerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/view/sfViewCacheManagerTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/view/sfViewParameterHolderTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method configuredView\\:\\:initialize\\(\\) should return bool but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/view/sfViewTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Method myView\\:\\:render\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/view/sfViewTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$_test_dir might not be defined\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/test/unit/view/sfViewTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Array has 2 duplicate keys with value \'w4\' \\(\'w4\', \'w4\'\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/widget/sfWidgetFormSchemaTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot unset offset 0 on sfWidgetFormSchema\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/widget/sfWidgetFormSchemaTest.php',
];
$ignoreErrors[] = [
	'message' => '#^Static method sfWidget\\:\\:fixDoubleEscape\\(\\) invoked with 3 parameters, 1 required\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/test/unit/widget/sfWidgetTest.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
