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

Doctrine & Project configuration
--------------------------------

Previously, you were able to configure doctrine in your `/config/ProjectConfiguration.class.php` using the method `configureDoctrine`.
This method isn't called anymore. You now need to connect to the `doctrine.configure` event:

```php
<?php
// ...

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    // ...

    $this->dispatcher->connect('doctrine.configure', array($this, 'configureDoctrineEvent'));
  }

  public function configureDoctrineEvent(sfEvent $event)
  {
    $manager = $event->getSubject();

    // configure what ever you want on the doctrine manager
    $manager->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
  }
}
```
