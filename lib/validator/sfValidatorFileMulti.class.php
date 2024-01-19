<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class sfValidatorFileMulti extends \sfValidatorFile
{
    /**
     * @see \sfValidatorBase
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
