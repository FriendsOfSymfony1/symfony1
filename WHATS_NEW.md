Service container
-----------------

A service container has been added (extracted from the [symfony dependency injection component](http://components.symfony-project.org/dependency-injection/)).  
[More details](https://github.com/LExpress/symfony1/wiki/ServiceContainer) about the integration on symfony core.

Form
----

Many issues have been fixed for embedded form included:

* You can use `sfFormObject::updateObject()` without save, all embedded form objects are updated.
* You can use file upload into your embedded forms, files will be correctly removed in embedded forms using `sfWidgetFormInputFileEditable`.
* `sfFormObject::updateObject()` and `sfFormObject::saveObject()` methods are call recursively from embedded forms.
* You can use integer in `name` argument of `sfForm::embedForm`.

This fixes the following tickets :

* [#4903](http://trac.symfony-project.org/ticket/4903): sfForm::embedForm($form) saves the $form before cloning | Add sfForm::getEmbeddedForm($name) and make the sfForm::embedForm($form) return the embedded form
* [#5805](http://trac.symfony-project.org/ticket/5805): extra_fields validation handled oddly with multi-level embedForm - causes error
* [#5867](http://trac.symfony-project.org/ticket/5867): Embedded forms do not call save() or doSave() on the form
* [#6937](http://trac.symfony-project.org/ticket/6937): Proposed patch to properly handle embedded (Doctrine) Forms
* [#7032](http://trac.symfony-project.org/ticket/7032): Embedded forms keep their CSRF token
* [#7440](http://trac.symfony-project.org/ticket/7440): Performance issue on sfValidatorErrorSchema::addError
* [#8500](http://trac.symfony-project.org/ticket/8500): sfWidgetFormInputFileEditable dont delete file when is in form embeded by embedRelation
* [#8800](http://trac.symfony-project.org/ticket/8800): Generated Embedded form with Many-To-Many relations do not save
* [#9147](http://trac.symfony-project.org/ticket/9147): sfFormDoctrine::processUploadedFile calls method/property on object without checking if it exists
* [#9172](http://trac.symfony-project.org/ticket/9172): removeFile in embeddedForms from sfFormDoctrine incorrect
* [#9637](http://trac.symfony-project.org/ticket/9637): Many to many in embedded form

**[BC Break]** The form is not cloned anymore when you passed it to `sfForm::embedForm`.

The method `sfForm::embedFormForForeach()` has been removed.

A new method `sfForm::getErrors()` has been added.  
This method returns an array with label as key and the validation error message as value (included embedded form errors).

The CRSF protection is now disable in cli environement.

Widget
------

A new parameter `default` has been added to the method `sfWidget::getOption`.

New widget `sfWidgetFormInputRead` has been added.  
This allow you to display a readonly input without border, with the value of your choice AND an hidden input with the real value and name for submit.

The method `sfWidgetFormDateRange::getStylesheets()` does not try to remove duplicate (fixes [#9224](http://trac.symfony-project.org/ticket/9224): *sfDoctrineCreateModelTablesTask should have an option to skip "build-model" task*).

Validator
---------

A new `sfValidatorIp` has been added (extracted from Symfony2).

Routing
-------

Routing part receive a huge performance improvement. Routes declared in cache are unserialized on demand.  
With the usage of combined `lookup_cache_dedicated_keys` and `cache` in `factories.yml`, only routes you use in page are instantiated.

When using routing cache, the cache key generated to identify a route or an URL can be customized by extending the `sfPatternRouting` class.

Action
------

When you overwrite a module from any plugin, it's not required anymore to copy all view files corresponding to the overloaded actions if you don't only need to modify action code.

Response
--------

Two new methods have been added :

* `sfWebResponse::clearJavascripts()`
* `sfWebResponse::clearStylesheet()`

This allow you to remove all stylesheets and/or all javascripts added into the global `view.yml` from action files without added a `view.yml` with `[-*]` in your module.

Request
-------

A new option `trust_proxy` has been added to `sfWebRequest`.  
You can set to false by adding `trust_proxy: false` in `request` section of your `factories.yml`.

If set to false, the following methods will not use the `HTTP_X_FORWARDED_*` server indication:

* `getHost()`
* `isSecure()`
* `getClientIp()`

The new `sfWebRequest::getClientIp()` method returns the client IP address that made the request.  
It takes a `proxy` parameter. If set to `false`, the IP will never come from proxy that passed the request.

Response
--------

A new method `sfWebResponse::prependTitle` has been added.  
This allow you to prepend title to the previous defined title, split by default by a `-` separator.

Filesystem
----------

`sfFilesystem::copy()` and `sfFilesystem::rename()` methods now return the result from `copy()` or `rename()` php functions (boolean).

Validator
---------

The method `sfValidatorSchema::preClean` now returns cleaned values (fixes [#5952](http://trac.symfony-project.org/ticket/5952): *preClean in sfValidatorSchema doesn't returns $values passed to it*).  
This allow you to modify into validators defined in your form `preValidator`.

The method `sfValidatorErrorSchema::addError` accept all possible name different of `null` as second argement.  
This allow you to set integer name for named error (fixes [#6112](http://trac.symfony-project.org/ticket/6112): *global errors are rendered on fields*).

Also, the method `sfValidatorErrorSchema::addError` uses much less memory for complex form with many (recursive) embedded forms.

**[BC Break]** The method `sfValidatorErrorSchema::addErrors` only accept a `sfValidatorErrorSchema` instance as argument.

The second argument (an array of errors) of the `sfValidatorErrorSchema` constructor should not be used anymore (marked as deprecated).

A new `sfValidatorEqual` has been added.  
It take one required `value` option and an optional `strict` to compare strictly or not.

The `sfValidatorFile` now returns size error in Kilo Byte instead of Byte.

A new method `sfValidator::resetType()` has been added.

**[BC Break]** The `trim` option of `sfValidatorBase` is set to `true` by default.

Component
---------

The `sfAction::renderJson` has been added.

Task
----

The debug mode of a task is now configurable.  
You can pass `--no-debug` option to any task, which will deactivate debug mode.

A new `sf_cli` configuration is available.  
This configuration is used to detect cli context (instead of using only debug mode).  
This allow to run a task in non-debug mode, usefull for using cache or using `project:optimize` for production environnement.

A new method `sfBaseTask::withTrace()` has been added.  
This is a proxy method to `sfCommandApplication::withTrace()` if available, returns `false` otherwise.

A new method `sfBaseTask::isVerbose()` has been added.  
This a proxy method to `sfCommandApplication::isVerbose()` if available, returns `false` otherwise.

A new method `sfBaseTask::showStatus()` has been added.  
This allow you to display a status bar with the progression, elapsed and reamaining time for repetitive actions in part of your task.

Helper
------

### Text

Two new arguments `truncate_pattern` and `max_length` (used only when `truncate_pattern` is set) have been added to the `truncate_text` function.  
See [unit tests](https://github.com/LExpress/symfony1/blob/master/test/unit/helper/TextHelperTest.php#L53) for usage examples.

### Asset

Two new functions `clear_javascripts` and `clear_stylesheets` have been added.  
This allow you to remove all stylesheets and/or all javascripts added into the global `view.yml` from view files.

Configuration
-------------

A new configuration `sf_upload_dir_name` contains 'uploads' has been added.

Performance
-----------

### Disable SwiftMailer for real

You can now completely disable SwiftMailer (which is initialized on **each** request by default) by using the new `sfNoMailer` class in your factories.yml:

    mailer:
      class: sfNoMailer

Test
----

A new option `test_path` has been added.  
This allow you to use another directory for unit tests temporary files storage.

Doctrine
--------

### Widget

A new `sfWidgetFormDoctrineArrayChoice` has been added.  
This allow you to use an array built by a table method of a model to increase performance.  
See [unit tests](https://github.com/LExpress/symfony1/blob/master/lib/plugins/sfDoctrinePlugin/test/unit/widget/sfWidgetFormDoctrineArrayChoiceTest.php) for usage examples.

### Form generation

Added column name for foreign key colums.  
This allow you to add a foreign key on a non primary key (works only for indexed columns on MySQL).

### Task

A new option `skip-build` has been added to `sfDoctrineCreateModelTablesTask`.  
This option allow you to skip the build model subtask called before the model creation.

The `doctrine:compile` task has been added and the generated file is automaticaly detect and use by symfony.

Tasks
-----

The `project:optimize` task has been fixed.

`php test/bin/coverage.php` take an optional argument to define only one file for coverage of a symfony class:

```bash
php test/bin/coverage.php sfWebRequest
```

Logger
------

You can now integrate a [psr compliant log](https://github.com/php-fig/log). For this you need to set the psr_logger
setting to true and configure a service with the id logger.psr.

```YAML
#In settings.yml
all:
  .settings:
    psr_logger: true
```

This is a example of service configuration using [monolog](https://github.com/Seldaek/monolog)

```YAML
#In services.yml

all:
  services:
    logger.psr:
      class: Monolog\Logger
      arguments: [default]
      calls:
        - [pushHandler, [@monolog.handler.file]]

    monolog.handler.file:
      class: Monolog\Handler\StreamHandler
      arguments: [/the/file/path/of/your/file.log]
```