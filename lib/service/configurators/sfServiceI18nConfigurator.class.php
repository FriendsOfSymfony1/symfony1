<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class sfServiceI18nConfigurator
 *
 * @package    symfony
 * @subpackage service
 * @author     Ilya Sabelnikov <fruit.dev@gmail.com>
 * @version    SVN: $Id$
 */
class sfServiceI18nConfigurator
{
  /**
   * @param sfI18N $i18n
   */
  public function configure(sfI18N $i18n)
  {
    sfWidgetFormSchemaFormatter::setTranslationCallable(array($i18n, '__'));
  }
}
