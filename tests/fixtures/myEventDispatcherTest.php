<?php

class myEventDispatcherTest
{
    public static function newMethod(sfEvent $event)
    {
        if ('newMethod' == $event['method']) {
            $arguments = $event['arguments'];
            $event->setReturnValue($arguments[0]);

            return true;
        }
    }
}
