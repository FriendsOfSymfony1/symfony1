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
class sfWidgetFormSchemaFormatterTable extends \sfWidgetFormSchemaFormatter
{
    protected $rowFormat = "<tr>\n  <th>%label%</th>\n  <td>%error%%field%%help%%hidden_fields%</td>\n</tr>\n";
    protected $errorRowFormat = "<tr><td colspan=\"2\">\n%errors%</td></tr>\n";
    protected $helpFormat = '<br />%help%';
    protected $decoratorFormat = "<table>\n  %content%</table>";
}
