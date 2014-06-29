<?php

##IP_CHECK##
require_once(__DIR__.'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('##APP_NAME##', '##ENVIRONMENT##', ##IS_DEBUG##);
sfContext::createInstance($configuration)->dispatch();
