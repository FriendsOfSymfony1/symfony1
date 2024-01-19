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
 * sfMessageSource_Aggregate aggregates several message source objects.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfMessageSource_Aggregate extends \sfMessageSource
{
    protected $messageSources = [];

    /**
     * Constructor.
     *
     * The order of the messages sources in the array is important.
     * This class will take the first translation found in the message sources.
     *
     * @param array $messageSources an array of message sources
     *
     * @see   MessageSource::factory();
     */
    public function __construct($messageSources)
    {
        $this->messageSources = $messageSources;
    }

    public function setCulture($culture)
    {
        parent::setCulture($culture);

        foreach ($this->messageSources as $messageSource) {
            $messageSource->setCulture($culture);
        }
    }

    /**
     * Determines if the source is valid.
     *
     * @return bool true if valid, false otherwise
     */
    public function isValidSource($sources)
    {
        foreach ($sources as $source) {
            if (false === $source[0]->isValidSource($source[1])) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * Gets the source, this could be a filename or database ID.
     *
     * @param string $variant catalogue+variant
     *
     * @return string the resource key
     */
    public function getSource($variant)
    {
        $sources = [];
        foreach ($this->messageSources as $messageSource) {
            $sources[] = [$messageSource, $messageSource->getSource(str_replace($messageSource->getId(), '', $variant))];
        }

        return $sources;
    }

    /**
     * Loads the message for a particular catalogue+variant.
     * This methods needs to implemented by subclasses.
     *
     * @return array of translation messages
     */
    public function &loadData($sources)
    {
        $messages = [];
        foreach ($sources as $source) {
            if (false === $source[0]->isValidSource($source[1])) {
                continue;
            }

            $data = $source[0]->loadData($source[1]);
            if (is_array($data)) {
                $messages = array_merge($data, $messages);
            }
        }

        return $messages;
    }

    /**
     * Gets all the variants of a particular catalogue.
     * This method must be implemented by subclasses.
     *
     * @param string $catalogue catalogue name
     *
     * @return array list of all variants for this catalogue
     */
    public function getCatalogueList($catalogue)
    {
        $variants = [];
        foreach ($this->messageSources as $messageSource) {
            foreach ($messageSource->getCatalogueList($catalogue) as $variant) {
                $variants[] = $messageSource->getId().$variant;
            }
        }

        return $variants;
    }

    /**
     * Adds a untranslated message to the source. Need to call save()
     * to save the messages to source.
     *
     * @param string $message message to add
     */
    public function append($message)
    {
        // Append to the first message source only
        if (count($this->messageSources)) {
            $this->messageSources[0]->append($message);
        }
    }

    /**
     * Updates the translation.
     *
     * @param string $text      the source string
     * @param string $target    the new translation string
     * @param string $comments  comments
     * @param string $catalogue the catalogue of the translation
     *
     * @return bool true if translation was updated, false otherwise
     */
    public function update($text, $target, $comments, $catalogue = 'messages')
    {
        // Only update one message source
        foreach ($this->messageSources as $messageSource) {
            if ($messageSource->update($text, $target, $comments, $catalogue)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Deletes a particular message from the specified catalogue.
     *
     * @param string $message   the source message to delete
     * @param string $catalogue the catalogue to delete from
     *
     * @return bool true if deleted, false otherwise
     */
    public function delete($message, $catalogue = 'messages')
    {
        $retval = false;
        foreach ($this->messageSources as $messageSource) {
            if ($messageSource->delete($message, $catalogue)) {
                $retval = true;
            }
        }

        return $retval;
    }

    /**
     * Saves the list of untranslated blocks to the translation source.
     * If the translation was not found, you should add those
     * strings to the translation source via the <b>append()</b> method.
     *
     * @param string $catalogue the catalogue to add to
     *
     * @return bool true if saved successfuly, false otherwise
     */
    public function save($catalogue = 'messages')
    {
        $retval = false;
        foreach ($this->messageSources as $messageSource) {
            if ($messageSource->save($catalogue)) {
                $retval = true;
            }
        }

        return $retval;
    }

    public function getId()
    {
        $id = '';
        foreach ($this->messageSources as $messageSource) {
            $id .= $messageSource->getId();
        }

        return md5($id);
    }

    /**
     * Returns a list of catalogue as key and all it variants as value.
     *
     * @return array list of catalogues
     */
    public function catalogues()
    {
        throw new \sfException('The "catalogues()" method is not implemented for this message source.');
    }

    /**
     * Gets the last modified unix-time for this particular catalogue+variant.
     *
     * @return int last modified in unix-time format
     */
    protected function getLastModified($sources)
    {
        $lastModified = time();
        foreach ($sources as $source) {
            if (0 !== $sourceLastModified = $source[0]->getLastModified($source[1])) {
                $lastModified = min($lastModified, $sourceLastModified);
            }
        }

        return $lastModified;
    }
}
