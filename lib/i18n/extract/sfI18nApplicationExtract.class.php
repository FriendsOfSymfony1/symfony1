<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfI18nApplicationExtract extends sfI18nExtract
{
    protected $extractObjects = [];

    /**
     * Configures the current extract object.
     */
    public function configure()
    {
        $this->extractObjects = [];

        // Modules
        $moduleNames = sfFinder::type('dir')->maxdepth(0)->relative()->in(sfConfig::get('sf_app_module_dir'));
        foreach ($moduleNames as $moduleName) {
            $this->extractObjects[] = new sfI18nModuleExtract($this->i18n, $this->culture, ['module' => $moduleName]);
        }
    }

    /**
     * Extracts i18n strings.
     *
     * This class must be implemented by subclasses.
     */
    public function extract()
    {
        foreach ($this->extractObjects as $extractObject) {
            $extractObject->extract();
        }

        // Add global templates
        $this->extractFromPhpFiles(sfConfig::get('sf_app_template_dir'));

        // Add global librairies
        $this->extractFromPhpFiles(sfConfig::get('sf_app_lib_dir'));
    }

    /**
     * Gets the current i18n strings.
     */
    public function getCurrentMessages()
    {
        return array_unique(array_merge($this->currentMessages, $this->aggregateMessages('getCurrentMessages')));
    }

    /**
     * Gets all i18n strings seen during the extraction process.
     */
    public function getAllSeenMessages()
    {
        return array_unique(array_merge($this->allSeenMessages, $this->aggregateMessages('getAllSeenMessages')));
    }

    protected function aggregateMessages($method)
    {
        $messages = [];
        foreach ($this->extractObjects as $extractObject) {
            $messages = array_merge($messages, $extractObject->{$method}());
        }

        return array_unique($messages);
    }
}
