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
 * sfWidgetFormInputHidden represents a hidden HTML input tag.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormInputHidden extends \sfWidgetFormInput
{
    /**
     * Configures the current widget.
     *
     * @param array $options    An array of options
     * @param array $attributes An array of default HTML attributes
     *
     * @see \sfWidgetFormInput
     */
    protected function configure($options = [], $attributes = [])
    {
        parent::configure($options, $attributes);

        $this->setOption('is_hidden', true);
        $this->setOption('type', 'hidden');
    }
}
