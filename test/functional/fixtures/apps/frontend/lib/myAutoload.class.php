<?php

class myAutoload
{
    public static function autoload($class)
    {
        if ('myAutoloadedClass' == $class) {
            require_once __DIR__.'/myAutoloadedClass.class.php';

            return true;
        }

        return false;
    }
}
