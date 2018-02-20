CHANGELOG
=========

02/08/2017: Version 1.5.10
--------------------------

* Add support of Throwables #144
* Fixed return type in PHPDoc #151
* Fix `sfEventLogger`: add priority and event.name overriding #160
* Don't try to autoload a trait if it's already been declared #162
* Fix/php 7.2 deprecation warnings #163

09/02/2017: Version 1.5.9
-------------------------

* Fix notice in sfValidatorSchema::getBytes" PHP 7.1 #148
* Fix dumpArray() for PHP 7.1 #146
* Fix some bugs with new PHP versions #143
* Fix yaml dumpArray() for PHP 7.1 #142

06/10/2016: Version 1.5.8
-------------------------

* PHP7 compatibility by @timmipetit #121
* sfMemcacheCache fix #124
* sfAutoload include fix for HHVM support #127
* sfAutoload include: eval only if forced on HHVM #130
* Fix phpdoc for sfDoctrineRecord::setDateTimeObject() #136
* Fix session data losing under certain situation #139

02/02/2016: Version 1.5.7
-------------------------

* Fix error in embedded form #105
* Added HTTP PATCH request method #110
* Fix integers used in mt_rand() #113
* Remove array cast from parameter of sfAction::renderJson #115
* Don't post unnamed submit fields in sfBrowserBase #112
* Add support for traits in autoloaders #112
* Php Inspections (EA Extended): Static Code Analysis #111

22/07/2015: Version 1.5.6
-------------------------

* Reverts #94. APCu 4.0.7+ is required for PHP 5.5+ #99

18/07/2015: Version 1.5.5
-------------------------

* Static Code Analysis with Php Inspections #97
* Change log level of sfException to CRITICAL #96

15/06/2015: Version 1.5.4
-------------------------

* Add blob support to yaml files #38
* Ability to integrate a psr compliant log #71
* Add OPTIONS method in sfRequest #75
* Add multiple file input #86
* Allows newer versions of Swiftmailer #90
* Allow to specify environment in sfI18nExtractTask #92

17/09/2014: Version 1.5.3
-------------------------

* Use late static binding for config classes #25
* Remove Doctrine from dependencies (add Doctrine and Propel as suggestions) #35
* Add relative option for PublishAssetsTask #43
* Add an option to test:all task to show full output #55
* Improve PHP built-in webserver compatibility #58
* Update Swiftmailer to ~5.2.1 #59
* Re-add Propel in sfDatabaseSessionStorage #66

10/30/2013: Version 1.5.2
-------------------------

 * Drop support for PHP 5.2
 * Add support for PHP 5.5

10/04/13: Version 1.5.0
-----------------------

 * inject the routing into the service container
 * decoupled routing cache key generation to make it customizable
 * optimized sfCompileConfigHandler and added unit tests
 * added possibility to use sfAPCCache without APC (same as sfNoCache)
 * added logger into service container for command
 * optimized sfPatternRouting cache with unserialise sfRoute objects on demand, huge gain with `lookup_cache_dedicated_keys`
 * added sf_cli core configuration
 * added default option --no-debug for tasks (usefull for project:optimize task)
 * used `is_file` instead of `file_exists` in sfFileCache, sfAutoload, sfApplicationConfiguration, sfProjectConfiguration and sfSessionTestStorage classes
 * added sfBaseTask::isVerbose() method
 * fixed "plugin:publish-assets" task generate absolute symlinks
 * fixed "project:optimize" task: file permissions, configurable environment and module generation
 * added task doctrine:compile to package the library into an unique cached file
 * added send event "application.throw_exception" when an exception occurs in a task
 * added exceptions catch in "cache:clear" task
 * added sfServiceContainer* classes to core_compile
 * cleaned some file class names, removed some require_once
 * fixed possible warning in sfWidgetFormSelectCheckbox
 * sfLogger implements sfLoggerInterface
 * added unit tests for yaml and event classes
 * added possibility to parse yaml for value when encoding is not utf-8
 * added sfWidgetFormDoctrineArrayChoice widget
 * added unit test for sfWidgetFormDoctrineChoice
 * added sfFormObject::saveObject() method
 * fixed sfValidatorFile error message for php.ini upload_max_filesize (inspired from Symfony2)
 * added sfForm::getErrors method
 * added possibility to set integer as name for named error
 * fixed: do not clone the form on embed anymore
 * removed unused sfWidgetFormSchemaForEach and sfValidatorSchemaForEach classes
 * added skip-build option to sfDoctrineCreateModelTablesTask (patch from @estahn)
 * better code coverage for sfWebRequest class unit tests
 * added parameters proxy to sfWebRequest::getClientIp() method (default true)
 * introduced trust_proxy option on sfWebRequest (default true)
 * added call to `fastcgi_finish_request()` function if available on sfWebResponse::run() method
 * added possibility to launch bin/coverage task for only one class
 * added sfRequest::getOption() method
 * used SQLite 3 for php 5.3 or later, as it's required for php 5.4
 * fixed sfI18NTest for php version > 5.2
 * added possibility for preValidator to modify values
 * displayed form errors with sfTestBrowser->with(form)->hasError()
 * added sfValidatorEqual
 * added test_path option to sfLimeHarness (patch by @stephaneerard)
 * allowed '-' character in key for sfToolkit::stringToArray (patch by @eXtreme)
 * added sfValidatorChoice::isEmpty method to test if value is an array with only empty value(s) (inspired by patch of @antitoxic)
 * added sfWebRequest::getClientIp() method
 * added sf_upload_dir_name to config
 * added sfValidatorIp (extracted from symfony2)
 * removed usage of array_unique in getStylesheets and getJavascripts methods of sfWidgetFormDateRange
 * fixed sfDoctrineRecordI18nFilter::filterGet() do not return empty translation (patch from @mahono)
 * fixed possible warning in sfDoctrineConnectionProfiler (patch from @mahono)
 * added sfWidgetFormInputRead
 * added sfWebResponse::prependTitle() method
 * added options truncate_pattern and max_lenght arguments to truncate_text function in TextHelper
 * added sfBaseTask::withTrace() and sfBaseTask::showStatus() methods
 * removed usage of array_map in widget
 * fixed sfValidatorFileTest for PHP >= 5.3.7
 * injected sf_event_dispatcher, sf_formatter and sf_logger to core service container
 * introduced recursive form embed (fix many embed form issues)
 * formatted sfValidatorFile error with KB instead of Bytes for readability
 * added sfComponent::renderJson() method
 * added foreign field name for column in foreign key field form definition
 * removed first choice only if it's strictly an empty string in sfValidatorDoctrineChoice
 * added sfValidatedFile::resetType() method
 * enabled trim option by default on sfValidatorBase
 * throw real exception on sfValidatorSchema and sfValidatorSchemaFilter
 * return php result for copy and rename methods of sfFilesystem
 * added clearJavascripts() and clearStylesheets() methods to sfWebResponse
 * added clear_javascripts and clear_stylesheets functions to AssetHelper
 * fixed sfAction::setTemplate() to allow view exists only in plugin dir when the module action is extended in application
 * added sfServiceConfigHandler
 * imported sfServiceContainer component
 * replaced embedded swiftmailer by upstream submodule of swiftmailer 4.1
 * removed sfPropelPlugin
 * add `sfNoMailer` class for application without mail
 * do no remove .* files when clearing the cache
 * Add helper __() to sfComponent to translate messages

05/30/12: Version 1.4.18
------------------------

 * [33466] fixed a possible DB session fixation attack (patch from Dmitri Groutso)
 * [33373] fixed test browser click function does not handle css selector without [ or ] (closes #9982, patch from mouette)

08/03/12: Version 1.4.17
------------------------

 * [33363] added some tests (closes #3237, patch from Stephen.Ostrow)
 * [33362] fixed a notice on PHP 5.4 (closes #9985, patch from bshaffer)
 * [33358] fixed Notice with PHP 5.4 (closes #10003)
 * [33309] fixed error in Debug mode from sfDebug.class.php when myUser implements sfSecurityUser (closes #9996)
 * [33299] reverted [33226] because of side effects (refs #8348)
 * [33292] fixed test for PHP 5.3 (patch from pylebecq)

12/13/11: Version 1.4.16
------------------------

 * [33251] fixed sfChoiceFormat when a string to translate contains a valid range (closes #9973)
 * [33250] fixed saving i18n fields in subforms (closes #7626, patch from yoshy71)
 * [33249] fixed last modified date calculation in sfMessageSource_Aggregate (closes #9981, patch from jamiel)
 * [33226] fixed merging problem for the routing configuration (closes #8348)
 * [33214] fixed ob_start() behavior on CLI (closes #9970)
 * [33208] fixed ob_start usage (to avoid warning in PHP 5.4, closes #9970)

10/27/11: Version 1.4.15
------------------------

 * [33151] fixed usage of mb_strlen in tasks (closes #9940)
 * [33149] added missing admin.delete_object event (closes #9943)
 * [33137] fixed multiple database support in Propel plugin (for the generator and sfPropelData, closes #8345)
 * [33125] fixed the possibility to include files included in an exclude rule in the deploy task (closes #9912)
 * [33122] fixed include|get_component when sfPartialView class is customized (closes #9932)
 * [33121] fixed protocol relative URL in the asset helper (closes #9936)
 * [33053] fixed typo (closes #9927, based on a patch from pmallet)

09/16/11: Version 1.4.14
------------------------

 * [33025] fixed sfCacheSessionStorage does not always check whether HTTP_USER_AGENT is set (closes #9921, patch from boutell)
 * [33022] fixed auto_link_text() when an email address is already linked (closes #9915, based on a patch from klemens_u)
 * [33021] fixed sfCacheSessionStorage does not serialize properly, also uses wrong option name for httponly (closes #9919, based on a patch from boutell)
 * [32939] fixed memory leak in sfRoute::generate() (closes #9886 - patch from acf0)
 * [32892] fixed Doctrine form generation when a schema has abstract tables (closes #9885 - patch from chok)
 * [32891] fixed sfFinder bug when mirroring file with twice the path inside it (closes #9892 - based on a patch from shordeaux)
 * [32890] fixed bad value of property 'list-style' in css of WebDebug toolbar (closes #9891 - patch from tomi)

08/05/11: Version 1.4.13
------------------------

 * [32707] fixed project:optimize which did not worked with plugins (closes #8760 - based on a patch from Pavel.Campr)
 * [32729] updated getHost() to consider a chain of forwarded hosts
 * [32740] fixed typo in error message (closes #9858)
 * [32741] removed non-countries from sfCultureInfo::getCountries() (closes #9848)
 * [32807] fixed parent for choice widgets (closes #8894)
 * [32808] simplified code from previous commit
 * [32810] fixed typo (closes #9866)
 * [32835] fixed previous commit when the renderer does not have the translate_choices option (see sfFormExtraPlugin for some examples)
 * [32836] fixed guessFromFileBinary in class sfvalidatorFile does not support MIME type including dot (closes #9871)
 * [32845] removed dead code (closes #9881)

07/01/11: Version 1.4.12
------------------------

 * [32634] fixed typo in an error message (closes #9762 - based on a patch from garak)
 * [32635] fixed typo (closes #9682)
 * [32637] added missing translations for fr (patch from Garfield-fr - closes #9025)
 * [32638] added missing translations for de (patch from _GeG_ - closes #9659)
 * [32639] fixed typo (closes #9561, patch from Elvis_the_King)
 * [32641] fixed extra code in stack trace (closes #9511, patch from tomi)
 * [32652] fixed sfRoute, which was not serializing some parameters (closes #9833)
 * [32653] fixed possible PHP notice (closes #9813)
 * [32654] fixed standard actions in sfObjectRouteCollection (HEAD is equivalent to GET, closes #9669, patch from ganchiku)
 * [32677] fixed missing information about Swiftmailer version (closes #9838)
 * [32678] fixed sfNumberFormat::format() when the value has a negative exponent (closes #9836)

03/30/11: Version 1.4.11
------------------------

 * Switched Doctrine external to our new mirror of the GitHub master branch

03/21/11: Version 1.4.10
------------------------

 * Updated to Doctrine 1.2.4 (security release)

02/04/11: Version 1.4.9
-----------------------

 * [31928] copied more values into a cachable partial's cloned response when the partial is marked as contextual in cache.yml (closes #7192)
 * [31895] fixed _auto_link_urls() does not pick up URLs with complex fragments (as per RFC3986 - closes #9424 - patch from benlancaster)
 * [31893] fixed sfDomCssSelector on class names with hyphens (patch from richsage, closes #9411)
 * [31471] changed the default value for session_cache_limiter to 'null'.
 * [31399] fixed sfViewCacheManager returns incorrect cached http_protocol (closes #9254)
 * [31254] fixed WDT injection (closes #9107)
 * [31249] fixed memory leak in Swift_DoctrineSpool
 * [31248] fixed sfI18nModuleExtract.class.php always assumes a file based message source (closes #9153 - patch from daReaper)
 * [31247] fixed mbstring problem in sfFilesystem (closes #9139 - based on a patch from nresni)
 * [31002] added call to parent::preExecute() to generated admin action classes (closes #9099)


09/24/10: Version 1.4.8
-----------------------

 * [30969] added more translations to german and italian translations (closes #9088)
 * [30968] fixed Persian Translation of admin generator (closes #8960)
 * [30967] fixed i18n support for traditional chinese (closes #8885)
 * [30966] updated Slovenian translations for Doctrine and Propel messages (closes #8985)
 * [30961] fixed missing WDT javascript (closes #9083, refs #9080)

09/21/10: Version 1.4.7
-----------------------

 * [30951] fixed WDT injects multiple times (closes #9080)
 * [30915] added support for "image/x-ms-bmp" mime type (closes #9069, patch from pbowyer)
 * [30912] fixed view class overriding (closes #5097, patch from caefer)
 * [30901] reverted remove of comments in Doctrine-generated table classes (closes #8880)
 * [30900] fixed getUriPrefix() when requested is forwarded from a secure one (closes #4723)
 * [30790] fixed logging of PHP errors to the WDT when error messages include a "%" character
 * [30563] fixed sfWebController::redirect method does not respect HTTP specification (closes #8952, patch from rande)
 * [30530] fixed path when a project is created on Windows and used on Linux (closes #8835)
 * [30529] fixed Swift_PropelSpool when the message is stored in a GLOB column (closes #8869, #8558, patch from netounet)
 * [30526] updated russian and ukrainian translation for the admin gen (closes #8814)
 * [30445] added a check whether doctrine is already loaded to allow use of a compiled core (closes #8917, thanks gnukix)
 * [30444] updated doctrine plugin to load Doctrine_Core rather than Doctrine
 * [30442] changed remaining calls to `Doctrine` to `Doctrine_Core`
 * [30441] Changed registration of Doctrine autoloader to use Doctrine_Core.

06/29/10: Version 1.4.6
-----------------------

 * [30031] added prevention of injected directory traversal in view cache (closes #8805)
 * [30008] fixed usage of shell_exec when the function is disabled (closes #8758)
 * [29990] added `sfOutputEscaperObjectDecorator::__isset()` (closes #8793)
 * [29818] fixed escaping of simple xml in PHP 5.2 (closes #8756)
 * [29716] Fixing default.css styling (fixes #7750)

05/31/10: Version 1.4.5
-----------------------

 * [29678] made sfForm::getName() more strict (closes #8318)
 * [29677] updated doctrine:clean task to delete form classes when generation has been disabled (closes #7777)
 * [29675] added translation of custom add_empty strings in Doctrine and Propel choice widgets (closes #8571)
 * [29674] updated date and time widgets so id_format is respected (closes #8446)
 * [29661] changed generation of (non-foreign key) primary key form fields so validation will fail if the primary key is changed (closes #8639, refs #8704)
 * [29643] Fixing issue with i18n forms when primary key and i18n field are not the defaults (fixes #8650)
 * [29641] Making css style in default admin gen css to be more specific (fixes #7750)
 * [29608] fixed directory permissions issue in sfFinder (closes #8684)
 * [29570] fixed doctrine form filter m2m query logic
 * [29553] fixed inconsistent EOL in Doctrine forms (closes #8075)
 * [29531] updated lime to 1.0.9
 * [29528] fixed warning in sfBasicSecurityUser when hasCredentials is called before credentials are set (closes #8512)
 * [29527] fixed notices in sfViewCacheManager::isCacheable() (closes #8527)
 * [29526] fixed incorrect http status when lockfile present (closes #8536)
 * [29524] fixed sfWebResponse::sendHttpHeaders() call from sfController::forward() in sfController::getPresentationFor() prevents later call to sendHttpHeaders() within main controller (closes #8568)
 * [29520] fixed warnings issued by the invalid array_flip() usage in sfI18N (refs #8522)
 * [29519] marked response as private when using the sfCacheSessionStorage class (closes #8535)
 * [29490] fixed deprecated delete in sfMemcacheCache (closes #8663)
 * [29417] fixed strip_links_text() from TextHelpers is defective and does not handle multiple links (closes #8589)
 * [29416] added admin generator i18n support for traditional chinese (closes #8633)
 * [29415] fixed sfProjectOptimizeTask on Windows (closes #8640)
 * [29390] Fixing default_params: { sf_format: xml } for route collections
 * [29309] fixed: Choices are not always automatically translated. Added option "translate_choices" that defaults to true (closes #7714)
 * [29285] fixed incorrect splitting of Accept-* headers (closes #8591)
 * [29218] updated Propel to 1.4.2
 * [29158] fixed sfDomCssSelector when selecting by attributes evaluating to false (closes #8120)
 * [29156] Fixed setting of Doctrine's default culture before records are initialized

04/06/10: Version 1.4.4
-----------------------

 * [29001] fixed sfPropelBaseTask should add propel include path by using sf_propel_runtime_path (thanks agallou, closes #8402)
 * [29000] fixed sfController::actionExists does not work if module is generated with admin-generator (closes #8427)
 * [28999] fixed fatal error in WDT when use_database is false but doctrine plugin is enabled
 * [28996] fixed merge of numeric field defaults, labels and helps in mergeForm
 * [28994] fixed merging of values from form to field schema (closes #8415)
 * [28992] updated doctrine form filter to check for NULL or an empty string on text and number fields for parity with the propel form filter (closes #7635)
 * [28988] fixed upgrade of yaml booleans followed by inline comments (closes #8342)
 * [28974] fixed merging of short syntax model definitions (refs #8449)
 * [28962] fixed a warning on lighttpd (closes #8417)
 * [28961] fixed sfWebController "redirect" method redirects a wrong place when there are more than two GET parameters (closes #8083)
 * [28958] fixed problem with return values for preDelete behavior for SfPropelBehaviorSymfonyBehaviors (closes #7872)
 * [28903] Adding missing arguments to embedRelation() (closes #8222)
 * [28902] Fixing issue with properly shutting down sfDoctrineDatabase and closing the Doctrine connection (closes #7081)
 * [28900] Fixing issue with form generator and string indexes instead of array (closes #8024)
 * [28898] Adding datetime to getDateTimeObject() and setDateTimeObject() (fixes #8116)
 * [28897] Fixing serialization issue with pager (fixes #7987)
 * [28871] Fixing issue with migrations diff autoloading (fixes #7272)
 * [28849] moved form_tag from FormHelper to UrlHelper to be consisten with symfony 1.4 (there is no consequence as the UrlHelper is always loaded, closes #7910)
 * [28848] fixed i18n:extract for generator.yml files (closes #8027, patch from alcaeus)
 * [28843] fixed i18n extractor keeps acumulating texts when more than 1 Heredoc string is used (closes #8166 - patch from gonrial)
 * [28840] fixed sfMailer::setDeliveryAddress() (closes #8306)
 * [28809] Throw exception when routing file cannot be written in doctrine:generate-admin task (fixes #8356)
 * [28785] fixed sort the parameters in order to compute the cache key the same way when parameters are submited in different order (closes #8457)
 * [28725] fixed invalid number formatting occurring with currency formats where no explicit negative format was defined (e.g for en_GB) (fixes #8433)
 * [28715] fixed sfProjectOptimizeTask should optimize only enabled modules (closes #8405)
 * [28714] fixed caching of 404 pages (closes #8339)
 * [28713] fixed layout.php is required even if you use decorate_with() to use another layout (closes #8441)
 * [28712] tweaked the check_configuration.php errors to be more explicit about what to do next (closes #8369)
 * [28703] fixed setting of culture in sfI18N constructor (closes #8444)
 * [28702] fixed browser to match more closely the behavior of a real browser (closes #7816)
 * [28642] added Persian Translation of admin generator (closes #8358)
 * [28640] fixed overflow for settings in the dev exception page (closes #8430)
 * [28633] fixed call to custom accessor (closes #8080)
 * [28632] fixed call to doCount (closes #7196)
 * [28625] fixed broken reference to response in the cache filter when a page cache is found
 * [28366] fixed sfGeneratorConfigHandler.class.php doesn't work with Windows path (closes #8301)
 * [28365] added a way to change the default max forward in the controller for edge cases (#8302)
 * [28353] fixed escaping of Doctrine query parameters in WDT
 * [28348] added a check for the php_posix extension as some distrib disable it (closes #8312)
 * [28347] removed cookies from Response objects serialization as it does not make any sense and can cause weird behaviors

02/25/10: Version 1.4.3
-----------------------

 * [28260] fixed sql injection vulnerability in doctrine admin generator

02/12/10: Version 1.4.2
-----------------------

 * [27954] fixed enabling of local csrf protection when disabled globally (closes #8228)
 * [27942] fixed output of doctrine:insert-sql task (closes #8008)
 * [27940] fixed field name used when propel unique validator throws a non-global error (closes #8108)
 * [27842] fixed typo, fixed consistent use of field rather than column name in doctrine form generators (closes #8254)
 * [27836] fixed submission of disable form fields by browser (closes #8178)
 * [27755] fixed double escaping of partial vars (closes #7825, refs #1638)
 * [27753] fixed helper signature (closes #8170)
 * [27752] fixed initialization of output escaper array iterator (closes #8202)
 * [27751] fixed symlink logic on vista+ with php 5.3 (closes #8237)
 * [27750] updated generated stub task to guess a default connection name based on ORM (closes #8209)
 * [27749] updated doctrine and propel forms to allow setting of defaults on numeric fields from within configure (closes #8238)
 * [27748] fixed form filtering by 0 on a number column (closes #8175)
 * [27747] fixed doctrine pager iteration (closes #7758, refs #8021)
 * [27742] fixed generation of enum pk form widgets (closes #7959)
 * [27738] fixed XSS hole in select checkbox and radio widgets (closes #8176)
 * [27736] fixed sfValidatorDoctrineChoice in cloned forms (embedForEach) doesn't function correctly (closes #8198)
 * [27616] passed the changeStack option in ->get() and ->post() calls of sfBrowserBase to the delegated ->call() (fixes #4271)
 * [27612] added basic test for sfPager->rewind() and fixed bug not leading to ->reset() not working correctly. (fixes #8021)
 * [27597] fixed minor incompatibility of new link_to() behaviour with 1.0 behaviour (fixes #7933, #8231)
 * [27511] fixed typo preventing sfProjectOptimizeTask to work correctly (closes #7885)
 * [27479] Removed svn version line from propel generated files showing them as modified even without changes each regeneration (backported r27472)
 * [27284] fixed empty class attributes in WDT markup (closes #8196)
 * [27211] added check and logging for non executable remote installer files in sfGenerateProjectTask (closes #7921)
 * [27183] fixed behavior when using either no separators or non slash separators for sfPatternRouting (fixes #8114)
 * [27061] partially fixed sfTester#isValid() on Windows systems (closes #7812)
 * [26989] fixed typo in getting Priorities from sfVarLogger (fixes #7938)
 * [26957] updated web debug javascript to work when the dom includes an svg element
 * [26872] fixed sfDomCssSelector requires quotes for matching attribute values when they should be optional (closes #8120)
 * [26871] fixed sfValidateDate for negative timestamps (closes #8134)
 * [26870] fixed sfWidgetFormSchema::setPositions() which accepts duplication positions (closes #7992)
 * [26867] turned off xdebug_logging by default as it can make the dev env very very slow (closes #8085)
 * [26866] fixed sfValidatorDate errors (closes #8118)
 * [26865] updated Propel to 1.4.1 (closes #8131)
 * [26681] fixed format_currency is rounding bad (closes #6788)
 * [25459] added the module name when including a partial in the admin generator
 * [25458] updated Turkish translations of the admin generator (closes #7928, patch by metoikos)
 * [25411] changed project:validate task to generate less false positive (closes #7852)
 * [25406] removed duplicate is_string check in sfWebController (closes #7918)
 * [25218] Fixing issue with disablePlugin() static method being called publicly while being defined protected

12/08/09: Version 1.4.1
-----------------------

 * [25063] updated class manipulator to work with mixed eol styles and no eol (closes #7694)
 * [25051] fixed typos in plugin manager
 * [25036] fixed php notices when test:* tasks are run outside of a command applications
 * [24993] updated checking for logged trace to be a bit more responsible (closes #7817)
 * [24992] added test coverage for testing browser redirects (refs #7823)
 * [24986] patched class manipulator to work with source that uses an eol other than PHP_EOL (closes #7694)
 * [24976] added translation for "Back To List" for German and Polish (fixes #7819)
 * [24972] added files not included in r24970
 * [24971] fixed sfFormDoctrine::removeFile fails to remove files (closes #7771)
 * [24970] fixed inclusion of linked doctrine schema files (closes #7774, thanks esycat)
 * [24962] using var export on serialisation to prevent invalid php code (fixes #7802)
 * [24944] removed duplicate declaration of options member variable (fixes #7809)
 * [24942] updated japanese translation of the admin generator (closes #7814 patch by river.bright)
 * [24849] fixed typo (closes #7778)
 * [24745] fixed replacing of tokens in doctrine stub and base model classes (closes #7656)
 * [24705] optimized project:validate task
 * [24703] Reverting r24632 (closes #6860)
 * [24701] Fixing strict standards notice

12/01/09: Version 1.4.0
-----------------------

 * [24637] fixed inconsistent case in doctrine crud (closes #7698, refs #5640)
 * [24634] Catching Doctrine validation exceptions so you don't get internal server errors in admin generator if you use Doctrine validation
 * [24632] Fixes issue with magic setters/getters for a field with a underscore and number at the end (closes #6860)
 * [24628] updated date validator to ignore date_format option if tainted value is an array (closes #7753, #7702)
 * [24625] updated doctrine:dql task to render NULL for null values when in table mode (closes #7680)
 * [24624] fixed warning with sfValidatorDate.class when a non string option was passed to it (fixes #7753, #7702)
 * [24622] allowed `__()` and `sfI18N->__()` and `sfMessageFormat->format()` to take an object with a `__toString()` method. Test case for 1.2,1.3 and 1.4 (fixes #7559, #6763, refs #2161)
 * [24621] fixed column name used when generating propel route collections (refs #5572, #6773)
 * [24620] fixed module option being ignored in *:generate-admin task (closes #5572, #6773)
 * [24619] fixed incorrect array access of lastModified header which only was an array pre 1.0. This was effectively preventing 304 Not Modified response from working correctly. Fixed phpdoc referring to array as return type of getHttpHeader() (fixes #6633, #7539)
 * [24618] Removing sfDoctrineRecordListener class which is not used (closes #7265)
 * [24617] Fixes issue with base model classes not having tokens replaced from properties.ini (closes #7656)
 * [24615] updated page and action caching to consider GET parameters (closes #4708)
 * [24607] no longer adding duplicate entries in sfMemcacheCache.class metadata cache when key is already existing (fixes #7602)
 * [24606] Fixing sfDoctrineRecord::__call() so proper exception is thrown (closes #7212)
 * [24605] refactored sfWidgetFormDate.class to allow easier extension and tests, as well as being easier to read (closes #7699)
 * [24604] Fix issue where local is an array (closes #6820)
 * [24598] Fixes issue with attributes in databases.yml (closes #6884)
 * [24597] fixed casting of propel i18n objects to string (closes #7709)
 * [24593] removed old lazy_cache_key setting from generator (closes #7720)
 * [24591] added requirements to DELETE action of sfObjectRouteCollection.class.php (fixes #7634)
 * [24590] fixed obtaining error from mysqli session storage (fixes #7737)
 * [24537] decoupled relation name from form field name when calling embedRelation(), allowed embedding of type "one" relations
 * [24532] updated spanish and basque translation of admin generator (fixes #7735 thanks Javier.Eguiluz)
 * [24531] fixed sfPager::count() implementation (it is more useful to return the total number of items, closes #7651)
 * [24524] fixed regression when cleaning a date string that includes a timezone in new `DateTime` implementation, added appropriate regression test to 1.2
 * [24514] improved vary cache generation. added unit test (refs #7605)
 * [24513] fixed vary cache key again (fixes #7605)
 * [24511] correctly closing output buffering in case of exceptions while requiring a file in sfPHPView (fixes #7596)
 * [24498] fixed getObjectsForParameters() failing on second invocation on sfDoctrineRoute.class.php (fixes #7716)
 * [24496] added missing where condition on culture to SfPropelBehaviorI18n (fixes #7713)
 * [24470] added a project:validate task that validates the project against the deprecated stuff
 * [24396] reset the mb_internal_encoding in case it was changed in text helpers. added basic unit test for that (fixes #7641)
 * [24395] updated upgrade task to specify a class for the common filter since it's no longer specified in the core (closes #7156, #7536)
 * [24390] removed call to deprecated sh() method
 * [24341] fixed fatal error in doctrine build/drop db tasks when no application exists (closes #7686, refs #7633)
 * [24339] optimized unshift of i18n filter to doctrine tables. big performance boost if you're working with many doctrine i18n records from the same table (closes #7392)
 * [24331] updated log:rotate to explicitly sort files by name and use filesystem methods when possible (closes #7683)

11/23/09: Version 1.4.0 RC2
---------------------------

 * [24295] removed deprecated `sfDoctrinePlugin_doctrine_lib_path` setting - use `sf_doctrine_dir` instead
 * [24294] Fixing issue with generators not respecting options of the parent who generated it (fixes #7639)
 * [24293] added missing API for getting Parameters of an sfRoute instance (closes #7632)
 * [24292] reverted the removal of the common filter when upgrading due to backward compatibility concerns. filter is still omitted for new projects (closes #7678)
 * [24288] fixed defaulting to first app when running a task with a project configuration already set (closes #7633, refs #5835)
 * [24281] added back the common filter to ease upgrading existing website (the default is still the same though) (refs #7657)
 * [24279] added missing PHPDoc (closes #7672)
 * [24278] added getOptions method to sfForm (closes #7613)
 * [24277] made exception messages more helpful (closes #7627)
 * [24275] added references to the reference guide in generated configuration files
 * [24271] updated token replacement in doctrine-generated model classes to disallow recursion into directories
 * [24270] updated token replacement in doctrine-generated model classes to allow recursion into plugin and base directories
 * [24265] fixed doc comments (closes #7664, #7666)
 * [24217] fixed embedded forms in functional tests (closes #7653)
 * [24215] fixed missing actions_base_class for Doctrine Generator (closes #7655, refs #5995)
 * [24150] enhanced doctrine:dql task to accept query parameters and render how long a query took
 * [24148] fixed forcing of colors in test:coverage task
 * [24137] fixed invalid id attributes generation in sfWidgetForm (closes #6980, based on a patch from Leon.van.der.Ree)
 * [24134] reverted yaml style (closes #7624)
 * [24132] fixed sfWidgetFormInputFileEditable (closes #7621)
 * [24130] fixed typo in propel I18N behavior
 * [24094] updated czech admin generator translation (fixes #7610, thanks to Pavel.Campr)
 * [24093] updated greek admin generator translation (fixes #7608, thanks to Zapantis Antreas)
 * [24092] updated polish admin generator translation (fixes #7608, thanks to m)
 * [24091] updated italian admin generator translation (fixes #7606, thanks to alexodus71)

11/16/09: Version 1.4.0 RC1
---------------------------

 * [24071] added script to help with formatting Subversion log for CHANGELOG
 * [24069] cleaned up template paths shown in WDT view panel
 * [24068] added check for generate*Filename method on form object to handle naming uploading files (closes #7350)
 * [24063] cleaned up generator templates (closes #7600)
 * [24062] fixed issue with autoloading not correctly sorted in windows (fixes #7226)
 * [24061] removed unneeded calls to setDefaultParameters by checking for a dirty flag.
 * [24060] added database arguments to doctrine create and drop database tasks (closes #7351)
 * [24056] reverted r23117 (refs #7363, closes #7456)
 * [24051] made all generated base classes abstract (closes #7301)
 * [24048] switched lime to new version 1.0.8
 * [24045] renamed listCredentials() as getCredentials(), removed the former in 1.4 (closes #7443)
 * [24043] added generic accessor for security.yml values
 * [24037] deprecated loading of helpers from the include path
 * [24036] fixed a bug with the / route that was made visible by r24026 (fixes #7597)
 * [24033] removed unnecessary call to sfConfig
 * [24032] moved project:optimize cache from project to application configuration
 * [24027] changed components dependencies to use the 1.0 branch
 * [24021] added short circuit checking for a static route prefix. Improves performance with many routes by up to 25%
 * [24020] added loadHelpers to project:optimize (closes #4556)
 * [24018] fixed command.* events not firing from generate:app task
 * [24015] Static texts in native widgets are translated by default (fixes #7590, patch by FabienP)
 * [24013] updated functional test bootstrap so fixture cache is always cleared before the context is created
 * [24012] Options within optgroups are translated correctly, optgroup labels are translated as well (fixes #7591)
 * [24008] ported r23909 to Propel 1.4 I18N behavior
 * [24007] removed unnecessary calls to sfConfig, cleaned up shortening of paths used in exception messages
 * [23995] reduced size of serialized sfRoute slightly due to the fact that defaultParameters will be always reset and compiled will be always true
 * [23994] Moved get/setParent() from sfWidgetFormSchema to sfWidgetForm. The choices of all select/choice widgets are now translated by default (fixes #5886)
 * [23993] removed obsolete setDefaultParameter code in routing
 * [23984] not setting status header for servers in cgi-sapi mode (fixes #3191)
 * [23977] fixed image saving for Doctrine
 * [23968] fixed i18n functional test not using deprectated redirect checking
 * [23967] corrected problem with validation when i18n is used. i18n should not be taken into account when the object is new (fixes #7486, patch by Dejan.Spasic)
 * [23954] updated phpdoc to reflect the actual possibilities for redirect parameters (refs #6082)
 * [23953] made empty redirect check faster and type tolerant (fixes #6082)
 * [23951] fixed file validator on certain mac os configurations (closes #6641)
 * [23950] fixed doctrine modules when dealing with multiple primary keys (closes #7571)
 * [23948] improved searching for symfony script file on windows (closes #6914)
 * [23930] fixed defaults for singular and plural name for generate crud tasks in case none are specified (refs #5640)
 * [23927] added a new option to change the generator class for forms and filters (closes #5014, patch from joostdj)
 * [23925] fixed sfMemcacheCache delete() operation (closes #6220)
 * [23924] fixed defaults for singular and plural name for generate crud tasks in case none are specified (refs #5640)
 * [23923] changed the routing handler cache file so that it consumes less memory
 * [23919] made crud generator respect singular and plural name (fixes #5640, patch by Dejan.Spasic)
 * [23917] Added sfFormField and sfFormFieldSchema to the safe classes in sfView. Widgets are always expected to be escaped by the developers! (fixes #7560, patch by nicolas)
 * [23915] added unit test for sfSessionStorage (closes #7585, patch by Rubino)
 * [23911] improved sfApplicationConfiguration getXYZDir caching when not using the project optimize task, by lazy caching (fixes #6413)
 * [23910] changed CLI tests to use the new admin generator instead of the old one
 * [23909] updated SfObjectBuilder so that Propel objects do not query the I18N table when they are new (fixes #7513, patch by joostdj)
 * [23907] improved sfApplicationConfiguration getXYZDir caching when not using the project optimize task, by lazy caching (fixes #6413)
 * [23901] The last exception is reset on every new page call in functional tests (fixes #6342, patch by Stefan.Koopmanschap)
 * [23900] Default fields are created for %%variables%% in the title of the edit, list and new action, if possible. Closes #7578
 * [23897] updated sfProtoculous javascript externals
 * [23896] switched phing external to 2.3.3 tag instead of its revision in the trunk
 * [23892] added Countable and Iterator interface to DOM CSS selector
 * [23888] reporting error on empty url for sfWebController#redirect (fixes #6082, patch by ThijsFeryn )
 * [23887] fixed Phing autoloading in upgrade task (fixes #7577, patch by Stefan.Koopmanschap)
 * [23882] sfI18N now correctly rejects invalid dates when using a culture that has the dot as separator, and correctly respects am/pm markers (fixes #7582)
 * [23852] fixed assumption in propel upgrade (closes #7577)
 * [23849] removed reference to removed file (closes #7563)
 * [23822] fixed loading of application-less plugin autoloader when multiple plugins are enabled
 * [23810] set svn:eol-style property to native and svn:keywords property to Id on all .php files
 * [23799] removed need for runtime insertion of lowercase module name into *_module_config.yml.php files. The module name is already inserted correctly by the sfDefineEnvironmentConfigHandler. This improves especially performance for projects with some more entries in module/config/module.yml (fixes #2105)
 * [23763] added some configuration to the project:send-emails task (number of messages and time limit)
 * [23762] updated Swift Mailer to the latest 4.1 version
