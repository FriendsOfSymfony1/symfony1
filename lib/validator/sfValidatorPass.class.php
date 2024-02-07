<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorPass is an identity validator. It simply returns the value unmodified.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorPass extends \sfValidatorBase
{
    /**
     * @see \sfValidatorBase
     */
    public function clean($value)
    {
        return $this->doClean($value);
    }

    /**
     * @see \sfValidatorBase
     */
    protected function doClean($value)
    {
        return $value;
    }
}
