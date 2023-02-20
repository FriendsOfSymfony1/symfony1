<?php

class sfValidatorFileMulti extends sfValidatorFile
{
    /**
     * @see sfValidatorBase
     *
     * @param mixed $value
     */
    protected function doClean($value)
    {
        $clean = array();

        foreach ($value as $file) {
            $clean[] = parent::doClean($file);
        }

        return $clean;
    }
}
