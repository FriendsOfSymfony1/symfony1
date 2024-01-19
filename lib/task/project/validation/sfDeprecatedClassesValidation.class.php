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
 * Finds deprecated classes usage.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfDeprecatedClassesValidation extends \sfValidation
{
    public function getHeader()
    {
        return 'Checking usage of deprecated classes';
    }

    public function getExplanation()
    {
        return [
            '',
            '  The files above use deprecated classes',
            '  that have been removed in symfony 1.4.',
            '',
            '  You can find a list of all deprecated classes under the',
            '  "Classes" section of the DEPRECATED tutorial:',
            '',
            '  http://www.symfony-project.org/tutorial/1_4/en/deprecated',
            '',
        ];
    }

    public function validate()
    {
        $classes = [
            'sfDoctrineLogger', 'sfNoRouting', 'sfPathInfoRouting', 'sfRichTextEditor',
            'sfRichTextEditorFCK', 'sfRichTextEditorTinyMCE', 'sfCrudGenerator', 'sfAdminGenerator',
            'sfPropelCrudGenerator', 'sfPropelAdminGenerator', 'sfPropelUniqueValidator', 'sfDoctrineUniqueValidator',
            'sfLoader', 'sfConsoleRequest', 'sfConsoleResponse', 'sfConsoleController',
            'sfDoctrineDataRetriever', 'sfPropelDataRetriever',
            'sfWidgetFormI18nSelectLanguage', 'sfWidgetFormI18nSelectCurrency', 'sfWidgetFormI18nSelectCountry',
            'sfWidgetFormChoiceMany', 'sfWidgetFormPropelChoiceMany', 'sfWidgetFormDoctrineChoiceMany',
            'sfValidatorChoiceMany', 'sfValidatorPropelChoiceMany', 'sfValidatorPropelDoctrineMany',
            'SfExtensionObjectBuilder', 'SfExtensionPeerBuilder', 'SfMultiExtendObjectBuilder',
            'SfNestedSetBuilder', 'SfNestedSetPeerBuilder', 'SfObjectBuilder', 'SfPeerBuilder',
            'sfWidgetFormPropelSelect', 'sfWidgetFormPropelSelectMany',
            'sfWidgetFormDoctrineSelect', 'sfWidgetFormDoctrineSelectMany',

            // classes from sfCompat10Plugin
            'sfEzComponentsBridge', 'sfZendFrameworkBridge', 'sfProcessCache', 'sfValidatorConfigHandler',
            'sfActionException', 'sfValidatorException', 'sfFillInFormFilter', 'sfValidationExecutionFilter',
            'sfRequestCompat10', 'sfFillInForm', 'sfCallbackValidator', 'sfCompareValidator', 'sfDateValidator',
            'sfEmailValidator', 'sfFileValidator', 'sfNumberValidator', 'sfRegexValidator', 'sfStringValidator',
            'sfUrlValidator', 'sfValidator', 'sfValidatorManager', 'sfMailView', 'sfMail',
        ];

        $found = [];
        $files = \sfFinder::type('file')->name('*.php')->prune('vendor')->in([
            \sfConfig::get('sf_apps_dir'),
            \sfConfig::get('sf_lib_dir'),
            \sfConfig::get('sf_test_dir'),
            \sfConfig::get('sf_plugins_dir'),
        ]);
        foreach ($files as $file) {
            $content = \sfToolkit::stripComments(file_get_contents($file));

            $matches = [];
            foreach ($classes as $class) {
                if (preg_match('#\b'.preg_quote($class, '#').'\b#', $content)) {
                    $matches[] = $class;
                }
            }

            if ($matches) {
                $found[$file] = implode(', ', $matches);
            }
        }

        return $found;
    }
}
