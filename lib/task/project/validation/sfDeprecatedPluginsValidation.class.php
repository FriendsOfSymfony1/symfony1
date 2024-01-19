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
 * Finds deprecated plugins usage.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfDeprecatedPluginsValidation extends \sfValidation
{
    public function getHeader()
    {
        return 'Checking usage of deprecated plugins';
    }

    public function getExplanation()
    {
        return [
            '',
            '  The files above use deprecated plugins',
            '  that have been removed in symfony 1.4.',
            '',
            'You can probably remove those references safely.',
            '',
        ];
    }

    public function validate()
    {
        $found = [];
        $files = \sfFinder::type('file')->name('*Configuration.class.php')->in($this->getProjectConfigDirectories());
        foreach ($files as $file) {
            $content = \sfToolkit::stripComments(file_get_contents($file));

            $matches = [];
            if (str_contains($content, 'sfCompat10Plugin')) {
                $matches[] = 'sfCompat10Plugin';
            }
            if (str_contains($content, 'sfProtoculousPlugin')) {
                $matches[] = 'sfProtoculousPlugin';
            }

            if ($matches) {
                $found[$file] = implode(', ', $matches);
            }
        }

        return $found;
    }
}
