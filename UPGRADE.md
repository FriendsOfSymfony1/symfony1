Information
===========

All tests of 1.4 release pass without any change of excepted values and none of them have been removed.

Warning
=======

Plugin
------

The sfPropelPlugin has been removed.  
Use the [sfPropelORMPlugin](https://github.com/propelorm/sfPropelORMPlugin) if you want to use the great Propel ORM.

Form
----

The `trim` option of `sfValidatorBase` is now set to `true` by default.

The method `sfForm::embedFormForForeach` have been removed.

Due to the new embed form enhancements:

* The form is not cloned anymore when it you embed it
* You cannot embed the same `sfForm` instance twice or more into an `sfForm`.
* You cannot added the same `sfValidatorErrorSchema` instance twice or more into an `sfValidatorErrorSchema`.
* The method `sfValidatorErrorSchema::addErrors` only accept an `sfValidatorErrorSchema` instance as argument.
+ The `sfValidatorErrorSchema` constructor no longer accept an array of errors as second argument.
