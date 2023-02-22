<?php

function sc_configure($instance)
{
    $instance->configure();
}

class BarClass
{
}

class BazClass
{
    public function configure($instance)
    {
        $instance->configure();
    }

    public static function getInstance()
    {
        return new self();
    }

    public static function configureStatic($instance)
    {
        $instance->configure();
    }

    public static function configureStatic1()
    {
    }
}
