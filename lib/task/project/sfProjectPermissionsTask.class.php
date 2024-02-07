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
 * Fixes symfony directory permissions.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfProjectPermissionsTask extends \sfBaseTask
{
    protected $current;
    protected $failed = [];

    /**
     * Captures those chmod commands that fail.
     *
     * @see http://www.php.net/set_error_handler
     *
     * @param \mixed|null $context
     */
    public function handleError($no, $string, $file, $line, $context = null)
    {
        $this->failed[] = $this->current;
    }

    /**
     * @see \sfTask
     */
    protected function configure()
    {
        $this->namespace = 'project';
        $this->name = 'permissions';
        $this->briefDescription = 'Fixes symfony directory permissions';

        $this->detailedDescription = <<<'EOF'
The [project:permissions|INFO] task fixes directory permissions:

  [./symfony project:permissions|INFO]
EOF;
    }

    /**
     * @see \sfTask
     */
    protected function execute($arguments = [], $options = [])
    {
        if (file_exists(\sfConfig::get('sf_upload_dir'))) {
            $this->chmod(\sfConfig::get('sf_upload_dir'), 0777);
        }

        $this->chmod(\sfConfig::get('sf_cache_dir'), 0777);
        $this->chmod(\sfConfig::get('sf_log_dir'), 0777);
        $this->chmod(\sfConfig::get('sf_root_dir').'/symfony', 0777);

        $dirs = [
            \sfConfig::get('sf_cache_dir'),
            \sfConfig::get('sf_log_dir'),
            \sfConfig::get('sf_upload_dir'),
        ];

        $dirFinder = \sfFinder::type('dir');
        $fileFinder = \sfFinder::type('file');

        foreach ($dirs as $dir) {
            $this->chmod($dirFinder->in($dir), 0777);
            $this->chmod($fileFinder->in($dir), 0666);
        }

        // note those files that failed
        if (count($this->failed)) {
            $this->logBlock(array_merge(
                ['Permissions on the following file(s) could not be fixed:', ''],
                array_map(function ($f) { return ' - '.\sfDebug::shortenFilePath($f); }, $this->failed)
            ), 'ERROR_LARGE');
        }
    }

    /**
     * Chmod and capture any failures.
     *
     * @param string $file
     * @param int    $mode
     * @param int    $umask
     *
     * @see \sfFilesystem
     */
    protected function chmod($file, $mode, $umask = 0000)
    {
        if (is_array($file)) {
            foreach ($file as $f) {
                $this->chmod($f, $mode, $umask);
            }
        } else {
            set_error_handler([$this, 'handleError']);

            $this->current = $file;
            @$this->getFilesystem()->chmod($file, $mode, $umask);
            $this->current = null;

            restore_error_handler();
        }
    }
}
