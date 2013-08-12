Service container
-----------------

A service container have been added (extracted from the [symfony dependency injection component](http://components.symfony-project.org/dependency-injection/).  
[More details](https://github.com/LExpress/symfony1/wiki/ServiceContainer) about the integration on symfony core.

Form
----

Many issues have been fixed for embedded form included:

* You can use `sfFormObject::updateObject()` without save, all embedded form objects are updated.
* You can use file upload into your embedded forms, files will be correctly removed in embedded forms using `sfWidgetFormInputFileEditable`.
* `sfFormObject::updateObject()` and `sfFormObject::saveObject()` methods are call recursivly from embeded forms.
* You can use integer in `name` argument of `sfForm::embedForm`.

This fixes the following tickets :

* http://trac.symfony-project.org/ticket/4903
* http://trac.symfony-project.org/ticket/5805
* http://trac.symfony-project.org/ticket/5867
* http://trac.symfony-project.org/ticket/6937
* http://trac.symfony-project.org/ticket/7032
* http://trac.symfony-project.org/ticket/7440
* http://trac.symfony-project.org/ticket/8500
* http://trac.symfony-project.org/ticket/8800
* http://trac.symfony-project.org/ticket/9147
* http://trac.symfony-project.org/ticket/9172
* http://trac.symfony-project.org/ticket/9637

[BC Break] The form is not cloned anymore when you passed it to `sfForm::embedForm`.

The method `sfForm::embedFormForForeach()` have been removed.

A new method `sfForm::getErrors()` have been added.  
This method returns an array with label as key and the validation error message as value (included embedded form errors).

The CRSF protection is now disable in cli environement.

Widget
------

A new parameter `default` have been added to the method `sfWidget::getOption`.

New widget `sfWidgetFormInputRead` have been added.  
This allow you to display a readonly input without border, with the value of your choice AND an hidden input with real value and name for submit.

The method `sfWidgetFormDateRange::getStylesheets()` does not try to remove duplicate (fixes http://trac.symfony-project.org/ticket/9224).

Validator
---------

A new `sfValidatorIp` have been added (extracted from symfony2).

Routing
-------

Routing part receive a huge performance improvement. Routes declared in cache are unserialize on demand.  
With the usage of combined `lookup_cache_dedicated_keys` and `cache` in `factories.yml`, only routes you use in page are instantiate.

When using routing cache, the cache key generated to identify a route or an URL can be customized bu extending the sfPatternRouting class.

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

A new option `trust_proxy` have been added to `sfWebRequest`.  
You can set to false by adding `trust_proxy: false` in `request` section of your `factories.yml`.

If set to false, the following methods will not use the `HTTP_X_FORWARDED_*` server indication:

* `getHost()`
* `isSecure()`
* `getClientIp()`

The new `sfWebRequest::getClientIp()` method returns the client IP address that made the request.  
It takes a `proxy` parameter. If set to `false`, the IP will never come from proxy that passed the request.

Response
--------

A new method `sfWebResponse::prependTitle` have been added.  
This allow you to prepend title to the previous defined title, split by default by a `-` separator.

Filesystem
----------

`sfFilesystem::copy()` and `sfFilesystem::rename()` methods now return the result from `copy()` or `rename()` php functions (boolean).

Validator
---------

The method `sfValidatorSchema::preClean` now returns cleaned values (fixes http://trac.symfony-project.org/ticket/5952).  
This allow you to modified into validotors defined in your form `preValidator.

The method `sfValidatorErrorSchema::addError` accept all possible name different of `null` as second argement.  
This allow you to set integer name for named error (fixes http://trac.symfony-project.org/ticket/6112).

Also, the method `sfValidatorErrorSchema::addError` uses much less memory for complex form with many (recursive) embedded forms.

[BC Break] The method `sfValidatorErrorSchema::addErrors` only accept an `sfValidatorErrorSchema` instance as argument.

The second argument (an array of errors) of the `sfValidatorErrorSchema` constructor should not be use anymore (mark as deprecated).

A new `sfValidatorEqual` have been added.  
It take one required `value` option an an optional `strict` to compare strictly or not.

The `sfValidatorFile` now returns size error in Kilo Byte instead of Byte.

A new method `sfValidator::resetType()` have been added.

[BC Break] The `trim` option of `sfValidatorBase` is set to `true` by default.

Component
---------

The `sfAction::renderJson` have been added.

Task
----

The debug mode of a task is now configurable.  
You can pass `--no-debug` option to any task, wish will desactivate debug mode.

A new `sf_cli` configuration is available.  
This configuration is now use to detect cli context (instead of using only debug mode).  
This allow to run task in non-debug mode, usefull for using cache or using `project:optimize` for production environnement.

A new method `sfBaseTask::withTrace()` have been added.  
This a proxy method to `sfCommandApplication::withTrace()` if available, returns `false` otherwise.

A new method `sfBaseTask::isVerbose()` have been added.  
This a proxy method to `sfCommandApplication::isVerbose()` if available, returns `false` otherwise.

A new method `sfBaseTask::showStatus()` have been added.  
This allow you to display a status bar with the progression, elapsed and reamaining time for repetitive actions in part of your task.

Helper
------

### Text

Two new arguments `truncate_pattern` and `max_lenght` (used only when `truncate_pattern` is set) have been added to the `truncate_text` function.  
See unit tests for usage examples.

### Asset

Two new functions `clear_javascripts` and `clear_stylesheets` have been added.  
This allow you to remove all stylesheets and/or all javascripts added into the global `view.yml` from view files.

Configuration
-------------

A new configuration `sf_upload_dir_name` contains 'uploads' have been added.

Performance
-----------

### Disable SwiftMailer for real

You can now completely disable SwiftMailer (which is initialized on **each** request by default) by using 
the new `sfNoMailer` class in your factories.yml:

    mailer:
      class: sfNoMailer

Test
----

A new option `test_path` have been added.  
This allow you to use another directory for unit tests temporary files storage.

Doctrine
--------

### Widget

A new `sfWidgetFormDoctrineArrayChoice` have been added.  
This allow you to use an array builded by a table method of a model to increase performance.  
See unit tests for usage examples.

### Form generation

Added column name for foreign key colums.  
This allow you to add foreign key on a non primary key (works only for indexed columns on Mysql).

### Task

A new option `skip-build` have been added to `sfDoctrineCreateModelTablesTask`.  
This option allow you to skip the build model subtask called before the model creation.

The `doctrine:compile` task has been added and the generated file is automaticaly detect and use by symfony.

Tasks
-----

The `project:optimize` task has been fixed.

`php test/bin/coverage.php` take an optional argument to define only one file for coverage of a symfony class:

```bash
php test/bin/coverage.php sfWebRequest
```
