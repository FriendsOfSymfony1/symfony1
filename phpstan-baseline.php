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
	'message' => '#^Access to an undefined property sfPearRest10\\:\\:\\$_rest\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugin/sfPearRest10.class.php',
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
	'message' => '#^Method sfDoctrineCli\\:\\:notify\\(\\) should return false but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/cli/sfDoctrineCli.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$stringName might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/database/sfDoctrineDatabase.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfWebDebugPanelDoctrine\\:\\:getTitle\\(\\) should return string but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/debug/sfWebDebugPanelDoctrine.class.php',
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
	'message' => '#^Call to an undefined method sfDoctrineFormGenerator\\:\\:isColumnNotNull\\(\\)\\.$#',
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
	'message' => '#^Variable \\$columns might not be defined\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/generator/sfDoctrineGenerator.class.php',
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
	'message' => '#^Variable \\$e2 in isset\\(\\) always exists and is not nullable\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/record/sfDoctrineRecord.class.php',
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
	'message' => '#^Method sfDoctrineCleanModelFilesTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineCleanModelFilesTask.class.php',
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
	'message' => '#^Method sfDoctrineInsertSqlTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineInsertSqlTask.class.php',
];
$ignoreErrors[] = [
	'message' => '#^Method sfDoctrineMigrateTask\\:\\:execute\\(\\) should return int but return statement is missing\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/lib/plugins/sfDoctrinePlugin/lib/task/sfDoctrineMigrateTask.class.php',
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

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
