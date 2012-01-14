Warning
=======

Plugin
------

The sfPropelPlugin has been removed.  
Use the [sfPropelORMPlugin](https://github.com/propelorm/sfPropelORMPlugin) if you want to use the great Propel ORM.

Form
----

The `trim` option of `sfValidatorBase` is now set to `true` by default.

Method `sfForm::embedFormForForeach` have been deleted.

Due to the new embed form management:

* you can't re-embed the same form twice or more into an sfForm.
* you can't re-added the same sfValidatorErrorSchema twice or more into an sfValidatorErrorSchema.
