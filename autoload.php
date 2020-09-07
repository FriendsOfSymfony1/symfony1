<?php

require_once __DIR__.'/lib/autoload/sfCoreAutoload.class.php';

sfCoreAutoload::register();

// Register sfMailerSwiftmailer6 as default sfMailer, if Swiftmailer ~6.0 has been loaded.
if(version_compare(Swift::VERSION, '6.0.0') >= 0) {
  sfConfig::set('sf_factory_mailer', 'sfMailerSwiftmailer6');
}
