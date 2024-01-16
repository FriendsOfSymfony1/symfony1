<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfPager class.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
abstract class sfPager implements Iterator, Countable
{
    protected $page = 1;
    protected $maxPerPage = 0;
    protected $lastPage = 1;
    protected $nbResults = 0;
    protected $class = '';
    protected $tableName = '';
    protected $objects;
    protected $cursor = 1;
    protected $parameters = [];
    protected $currentMaxLink = 1;
    protected $parameterHolder;
    protected $maxRecordLimit = false;
    // used by iterator interface
    protected $results;
    protected $resultsCounter = 0;

    /**
     * Constructor.
     *
     * @param string $class      The model class
     * @param int    $maxPerPage Number of records to display per page
     */
    public function __construct($class, $maxPerPage = 10)
    {
        $this->setClass($class);
        $this->setMaxPerPage($maxPerPage);
        $this->parameterHolder = new sfParameterHolder();
    }

    /**
     * Initialize the pager.
     *
     * Function to be called after parameters have been set.
     */
    abstract public function init();

    /**
     * Returns an array of results on the given page.
     *
     * @return array
     */
    abstract public function getResults();

    /**
     * Returns the current pager's max link.
     *
     * @return int
     */
    public function getCurrentMaxLink()
    {
        return $this->currentMaxLink;
    }

    /**
     * Returns the current pager's max record limit.
     *
     * @return int
     */
    public function getMaxRecordLimit()
    {
        return $this->maxRecordLimit;
    }

    /**
     * Sets the current pager's max record limit.
     *
     * @param int $limit
     */
    public function setMaxRecordLimit($limit)
    {
        $this->maxRecordLimit = $limit;
    }

    /**
     * Returns an array of page numbers to use in pagination links.
     *
     * @param int $nb_links The maximum number of page numbers to return
     *
     * @return array
     */
    public function getLinks($nb_links = 5)
    {
        $links = [];
        $tmp = $this->page - floor($nb_links / 2);
        $check = $this->lastPage - $nb_links + 1;
        $limit = $check > 0 ? $check : 1;
        $begin = $tmp > 0 ? ($tmp > $limit ? $limit : $tmp) : 1;

        $i = (int) $begin;
        while ($i < $begin + $nb_links && $i <= $this->lastPage) {
            $links[] = $i++;
        }

        $this->currentMaxLink = count($links) ? $links[count($links) - 1] : 1;

        return $links;
    }

    /**
     * Returns true if the current query requires pagination.
     *
     * @return bool
     */
    public function haveToPaginate()
    {
        return $this->getMaxPerPage() && $this->getNbResults() > $this->getMaxPerPage();
    }

    /**
     * Returns the current cursor.
     *
     * @return int
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    /**
     * Sets the current cursor.
     *
     * @param int $pos
     */
    public function setCursor($pos)
    {
        if ($pos < 1) {
            $this->cursor = 1;
        } elseif ($pos > $this->nbResults) {
            $this->cursor = $this->nbResults;
        } else {
            $this->cursor = $pos;
        }
    }

    /**
     * Returns an object by cursor position.
     *
     * @param int $pos
     */
    public function getObjectByCursor($pos)
    {
        $this->setCursor($pos);

        return $this->getCurrent();
    }

    /**
     * Returns the current object.
     */
    public function getCurrent()
    {
        return $this->retrieveObject($this->cursor);
    }

    /**
     * Returns the next object.
     *
     * @return mixed|null
     */
    public function getNext()
    {
        if ($this->cursor + 1 > $this->nbResults) {
            return null;
        }

        return $this->retrieveObject($this->cursor + 1);
    }

    /**
     * Returns the previous object.
     *
     * @return mixed|null
     */
    public function getPrevious()
    {
        if ($this->cursor - 1 < 1) {
            return null;
        }

        return $this->retrieveObject($this->cursor - 1);
    }

    /**
     * Returns the first index on the current page.
     *
     * @return int
     */
    public function getFirstIndice()
    {
        if (0 == $this->page) {
            return 1;
        }

        return ($this->page - 1) * $this->maxPerPage + 1;
    }

    /**
     * Returns the last index on the current page.
     *
     * @return int
     */
    public function getLastIndice()
    {
        if (0 == $this->page) {
            return $this->nbResults;
        }

        if ($this->page * $this->maxPerPage >= $this->nbResults) {
            return $this->nbResults;
        }

        return $this->page * $this->maxPerPage;
    }

    /**
     * Returns the current class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets the current class.
     *
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Returns the number of results.
     *
     * @return int
     */
    public function getNbResults()
    {
        return $this->nbResults;
    }

    /**
     * Returns the first page number.
     *
     * @return int
     */
    public function getFirstPage()
    {
        return 1;
    }

    /**
     * Returns the last page number.
     *
     * @return int
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    /**
     * Returns the current page.
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Returns the next page.
     *
     * @return int
     */
    public function getNextPage()
    {
        return min($this->getPage() + 1, $this->getLastPage());
    }

    /**
     * Returns the previous page.
     *
     * @return int
     */
    public function getPreviousPage()
    {
        return max($this->getPage() - 1, $this->getFirstPage());
    }

    /**
     * Sets the current page.
     *
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = (int) $page;

        if ($this->page <= 0) {
            // set first page, which depends on a maximum set
            $this->page = $this->getMaxPerPage() ? 1 : 0;
        }
    }

    /**
     * Returns the maximum number of results per page.
     *
     * @return int
     */
    public function getMaxPerPage()
    {
        return $this->maxPerPage;
    }

    /**
     * Sets the maximum number of results per page.
     *
     * @param int $max
     */
    public function setMaxPerPage($max)
    {
        if ($max > 0) {
            $this->maxPerPage = $max;
            if (0 == $this->page) {
                $this->page = 1;
            }
        } elseif (0 == $max) {
            $this->maxPerPage = 0;
            $this->page = 0;
        } else {
            $this->maxPerPage = 1;
            if (0 == $this->page) {
                $this->page = 1;
            }
        }
    }

    /**
     * Returns true if on the first page.
     *
     * @return bool
     */
    public function isFirstPage()
    {
        return 1 == $this->page;
    }

    /**
     * Returns true if on the last page.
     *
     * @return bool
     */
    public function isLastPage()
    {
        return $this->page == $this->lastPage;
    }

    /**
     * Returns the current pager's parameter holder.
     *
     * @return sfParameterHolder
     */
    public function getParameterHolder()
    {
        return $this->parameterHolder;
    }

    /**
     * Returns a parameter.
     *
     * @param string     $name
     * @param mixed|null $default
     */
    public function getParameter($name, $default = null)
    {
        return $this->parameterHolder->get($name, $default);
    }

    /**
     * Checks whether a parameter has been set.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasParameter($name)
    {
        return $this->parameterHolder->has($name);
    }

    /**
     * Sets a parameter.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setParameter($name, $value)
    {
        $this->parameterHolder->set($name, $value);
    }

    /**
     * Returns the current result.
     *
     * @see Iterator
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        return current($this->results);
    }

    /**
     * Returns the current key.
     *
     * @see Iterator
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        return key($this->results);
    }

    /**
     * Advances the internal pointer and returns the current result.
     *
     * @see Iterator
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        --$this->resultsCounter;

        return next($this->results);
    }

    /**
     * Resets the internal pointer and returns the current result.
     *
     * @see Iterator
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        $this->resultsCounter = count($this->results);

        return reset($this->results);
    }

    /**
     * Returns true if pointer is within bounds.
     *
     * @see Iterator
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        if (!$this->isIteratorInitialized()) {
            $this->initializeIterator();
        }

        return $this->resultsCounter > 0;
    }

    /**
     * Returns the total number of results.
     *
     * @see Countable
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return $this->getNbResults();
    }

    /**
     * Returns an object at a certain offset.
     *
     * Used internally by {@link getCurrent()}.
     *
     * @param int $offset
     */
    abstract protected function retrieveObject($offset);

    /**
     * Sets the number of results.
     *
     * @param int $nb
     */
    protected function setNbResults($nb)
    {
        $this->nbResults = $nb;
    }

    /**
     * Sets the last page number.
     *
     * @param int $page
     */
    protected function setLastPage($page)
    {
        $this->lastPage = $page;

        if ($this->getPage() > $page) {
            $this->setPage($page);
        }
    }

    /**
     * Returns true if the properties used for iteration have been initialized.
     *
     * @return bool
     */
    protected function isIteratorInitialized()
    {
        return null !== $this->results;
    }

    /**
     * Loads data into properties used for iteration.
     */
    protected function initializeIterator()
    {
        $this->results = $this->getResults();
        $this->resultsCounter = count($this->results);
    }

    /**
     * Empties properties used for iteration.
     */
    protected function resetIterator()
    {
        $this->results = null;
        $this->resultsCounter = 0;
    }
}
