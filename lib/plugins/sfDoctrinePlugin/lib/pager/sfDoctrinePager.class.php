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
 * sfDoctrine pager class.
 *
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 *
 * @version    SVN: $Id$
 */
class sfDoctrinePager extends \sfPager implements \Serializable
{
    protected $query;
    protected $tableMethodName;
    protected $tableMethodCalled = false;

    /**
     * Serializes the current instance for php 7.4+.
     *
     * @return array
     */
    public function __serialize()
    {
        $vars = get_object_vars($this);
        unset($vars['query']);

        return $vars;
    }

    /**
     * Unserializes a sfDoctrinePager instance for php 7.4+.
     *
     * @param array $data
     */
    public function __unserialize($data)
    {
        foreach ($data as $name => $values) {
            $this->{$name} = $values;
        }

        $this->tableMethodCalled = false;
    }

    /**
     * Get the name of the table method used to retrieve the query object for the pager.
     *
     * @return string $tableMethodName
     */
    public function getTableMethod()
    {
        return $this->tableMethodName;
    }

    /**
     * Set the name of the table method used to retrieve the query object for the pager.
     *
     * @param string $tableMethodName
     */
    public function setTableMethod($tableMethodName)
    {
        $this->tableMethodName = $tableMethodName;
    }

    /**
     * Serialize the pager object.
     *
     * @return string $serialized
     */
    public function serialize()
    {
        return serialize($this->__serialize());
    }

    /**
     * Unserialize a pager object.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $array = unserialize($serialized);

        return $this->__unserialize($array);
    }

    /**
     * Returns a query for counting the total results.
     *
     * @return \Doctrine_Query
     */
    public function getCountQuery()
    {
        $query = clone $this->getQuery();
        $query
            ->offset(0)
            ->limit(0)
        ;

        return $query;
    }

    /**
     * @see \sfPager
     */
    public function init()
    {
        $this->resetIterator();

        $countQuery = $this->getCountQuery();
        $count = $countQuery->count();

        $this->setNbResults($count);

        $query = $this->getQuery();
        $query
            ->offset(0)
            ->limit(0)
        ;

        if (0 == $this->getPage() || 0 == $this->getMaxPerPage() || 0 == $this->getNbResults()) {
            $this->setLastPage(0);
        } else {
            $offset = ($this->getPage() - 1) * $this->getMaxPerPage();

            $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));

            $query
                ->offset($offset)
                ->limit($this->getMaxPerPage())
            ;
        }
    }

    /**
     * Get the query for the pager.
     *
     * @return \Doctrine_Query
     */
    public function getQuery()
    {
        if (!$this->tableMethodCalled && $this->tableMethodName) {
            $method = $this->tableMethodName;
            $this->query = \Doctrine_Core::getTable($this->getClass())->{$method}($this->query);
            $this->tableMethodCalled = true;
        } elseif (!$this->query) {
            $this->query = \Doctrine_Core::getTable($this->getClass())->createQuery();
        }

        return $this->query;
    }

    /**
     * Set query object for the pager.
     *
     * @param \Doctrine_Query $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * Get all the results for the pager instance.
     *
     * @param mixed $hydrationMode A hydration mode identifier
     *
     * @return array|Doctrine_Collection
     */
    public function getResults($hydrationMode = null)
    {
        return $this->getQuery()->execute([], $hydrationMode);
    }

    /**
     * Retrieve the object for a certain offset.
     *
     * @param int $offset
     *
     * @return \Doctrine_Record
     */
    protected function retrieveObject($offset)
    {
        $queryForRetrieve = clone $this->getQuery();
        $queryForRetrieve
            ->offset($offset - 1)
            ->limit(1)
        ;

        $results = $queryForRetrieve->execute();

        return $results[0];
    }

    /**
     * @see \sfPager
     */
    protected function initializeIterator()
    {
        parent::initializeIterator();

        if ($this->results instanceof \Doctrine_Collection) {
            $this->results = $this->results->getData();
        }
    }
}
