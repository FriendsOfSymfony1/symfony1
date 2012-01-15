Service container
-----------------

A service container have been added (extracted from the [symfony dependency injection component](http://components.symfony-project.org/dependency-injection/).  
[More details](https://github.com/LExpress/symfony1/wiki/ServiceContainer) about the integration on symfony core.

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

The `sfValidatorFile` now returns size error in Kilo Byte instead of Byte.

A new method `sfValidator::resetType()` have been added.

[BC Break] The `trim` option of `sfValidatorBase` is set to `true` by default.

Component
---------

The `sfAction::renderJson` have been added.

Task
----

A new method `sfBaseTask::withTrace()` have been added.  
This a proxy method to `sfCommandApplication::withTrace()` if available, returns `false` otherwise.

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

A new option `test_path` have been added.  
This allow you to use another directory for unit tests temporary files storage.

Doctrine
--------

### Form generation

Added column name for foreign key colums.  
This allow you to add foreign key on a non primary key (works only for indexed columns on Mysql).

Tasks
-----

`php test/bin/coverage.php` take an optional argument to define only one file for coverage of a symfony class:

```bash
php test/bin/coverage.php sfWebRequest
```
