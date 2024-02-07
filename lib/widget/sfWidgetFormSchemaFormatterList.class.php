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
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfWidgetFormSchemaFormatterList extends \sfWidgetFormSchemaFormatter
{
    protected $rowFormat = "<li>\n  %error%%label%\n  %field%%help%\n%hidden_fields%</li>\n";
    protected $errorRowFormat = "<li>\n%errors%</li>\n";
    protected $helpFormat = '<br />%help%';
    protected $decoratorFormat = "<ul>\n  %content%</ul>";
}
