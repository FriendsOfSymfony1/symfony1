Service container
-----------------

A service container have been added (extracted from the [symfony dependency injection component](http://components.symfony-project.org/dependency-injection/).  
[More details](https://github.com/LExpress/symfony1/wiki/ServiceContainer) about the integration on symfony core.

Form
----

Many issues have been fixed for embedded form included:

* You can use `sfFormObject::updateObject()` without save, all embedded form objects are updated.
* You can use file upload into your embedded forms, files will be correctly removed in embedded forms using `sfWidgetFormInputFileEditable`.
* `sfFormObject::updateObject()` and `sfFormObject::save()` methods are call recursivly from embeded forms.
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

Widget
------

A new parameter `default` have been added to the method `sfWidget::getOption`.

New widget `sfWidgetFormInputRead` have been added.  
This allow you to display a readonly input without border, with the value of your choice AND an hidden input with real value and name for submit.

The method `sfWidgetFormDateRange::getStylesheets()` does not try to remove duplicate (fixes http://trac.symfony-project.org/ticket/9224).

Validator
---------

A new `sfValidatorIp`have been added (extracted from symfony2).

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

[BC Break] The `sfValidatorErrorSchema` constructor no longer accept an array of errors as second argument.

The `sfValidatorFile` now returns size error in Kilo Byte instead of Byte.

A new method `sfValidator::resetType()` have been added.

[BC Break] The `trim` option of `sfValidatorBase` is set to `true` by default.

Component
---------

The `sfAction::renderJson` have been added.

Task
----

A new method `sfBaseTask::withTrace()` have been added. This a proxy method to `sfCommandApplication::withTrace()` if available, returns `false` otherwise.

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

Test
----

A new option `test_path` have been added. This allow you to use another directory for unit tests temporary files storage.

Doctrine
--------

### Form generation

Added column name for foreign key colums. This allow you to add foreign key on a non primary key (works only for indexed columns on Mysql).

Tasks
-----

`php test/bin/coverage.php` take an optional argument to define only one file for coverage of a symfony class:

```bash
php test/bin/coverage.php sfWebRequest
```
