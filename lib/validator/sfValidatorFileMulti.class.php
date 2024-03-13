<?php

class sfValidatorFileMulti extends sfValidatorFile
{
    /**
     * @see sfValidatorBase
     */
    protected function doClean($value)
    {
        $clean = [];

        foreach ($value as $file) {
            $clean[] = parent::doClean($file);
        }

        return $clean;
    }
}
