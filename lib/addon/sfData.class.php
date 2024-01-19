<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This class defines the interface for interacting with data, as well
 * as default implementations.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfData
{
    protected $deleteCurrentData = true;
    protected $object_references = [];

    /**
     * Sets a flag to indicate if the current data in the database
     * should be deleted before new data is loaded.
     *
     * @param bool $boolean The flag value
     */
    public function setDeleteCurrentData($boolean)
    {
        $this->deleteCurrentData = $boolean;
    }

    /**
     * Gets the current value of the flag that indicates whether
     * current data is to be deleted or not.
     *
     * @return bool
     */
    public function getDeleteCurrentData()
    {
        return $this->deleteCurrentData;
    }

    /**
     * Manages the insertion of data into the data source.
     *
     * @param array $data The data to be inserted into the data source
     */
    abstract public function loadDataFromArray($data);

    /**
     * Gets a list of one or more *.yml files and returns the list in an array.
     *
     * The returned array of files is sorted by alphabetical order.
     *
     * @param array|string $element A directory or file name or an array of directories and/or file names
     *                              If null, then defaults to 'sf_data_dir'/fixtures
     *
     * @return array A list of *.yml files
     *
     * @throws sfInitializationException if the directory or file does not exist
     */
    public function getFiles($element = null)
    {
        if (null === $element) {
            $element = sfConfig::get('sf_data_dir').'/fixtures';
        }

        $files = [];
        if (is_array($element)) {
            foreach ($element as $e) {
                $files = array_merge($files, $this->getFiles($e));
            }
        } elseif (is_file($element)) {
            $files[] = $element;
        } elseif (is_dir($element)) {
            $files = sfFinder::type('file')->name('*.yml')->sort_by_name()->in($element);
        } else {
            throw new sfInitializationException(sprintf('You must give an array, a directory or a file to sfData::getFiles() (%s given).', $element));
        }

        $files = array_unique($files);
        sort($files);

        return $files;
    }

    /**
     * Loads data for the database from a YAML file.
     *
     * @param string $file the path to the YAML file
     */
    protected function doLoadDataFromFile($file)
    {
        // import new datas
        $data = sfYaml::load($file, sfConfig::get('sf_charset', 'UTF-8'));

        $this->loadDataFromArray($data);
    }

    /**
     * Manages reading all of the fixture data files and
     * loading them into the data source.
     *
     * @param array $files The path names of the YAML data files
     */
    protected function doLoadData(array $files)
    {
        $this->object_references = [];
        $this->maps = [];

        foreach ($files as $file) {
            $this->doLoadDataFromFile($file);
        }
    }
}
