Service container
-----------------

We now provide a service container available via a factory (extracted from the [symfony dependency injection component](http://components.symfony-project.org/dependency-injection/)).

Action
------

When you overwrite a module from any plugin, it's not required anymore to copy all view files corresponding to the overloaded actions if you don't only need to modify action code.

Response
--------

Two new methods are available :

* clearJavascripts
* clearStylesheet

This allow you to remove all stylesheets and all javascripts added into the gloval view.yml from actions file without added a view.yml with [-*] in your module.

Request
-------

Added getClientIp and getRealIp methods to sfWebRequest.

Filesystem
----------

Methods copy() and rename() return the result from copy() or rename() php functions (boolean).

Validator
---------

sfValidatorFile now return size error in Kilo Byte instead of Byte.

sfValidator file has now a resetType() method.

[BC Break] The trim option of sfValidatorBase is now set to true by default.

Component
---------

Added method renderJson (take an array as parameter).

Task
----

Added withTrace proxy method.

Added showStatus method. You can display a status bar show your avancement, elapsed and reamaining time for repetitive actions in part of your task.

Helper
------

### Text

Added arguments truncate_pattern and max_lenght (used only when truncate_pattern is set) to the truncate_text function.
See unit tests for usage examples.

### Asset

Added clear_javascripts and clear_stylesheets functions.

Configuration
-------------

Added sf_upload_dir_name contains 'uploads'.

Test
----

Added test_path option. It allow you to use another directory for unit tests temporary files.

Doctrine
--------

### Form generation

Added column name for foreign key colums. It allow you to add foreign key on a non primary key.
