<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include dirname(__FILE__).'/../bootstrap/unit.php';

$t = new \lime_test(23);

$conn = \Doctrine_Manager::connection(new \Doctrine_Adapter_Mock('mysql'));

/**
 * @internal
 *
 * @coversNothing
 */
class Test extends \sfDoctrineRecord
{
    public function setUp()
    {
        $this->hasMany('TestRelation as TestRelations', ['local' => 'id', 'foreign' => 'test_id']);
    }

    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255, ['notblank' => true]);
        $this->hasColumn('test as TEST', 'string', 255);
        $this->hasColumn('email', 'string', 255, ['email' => true, 'notnull' => true]);
    }
}

class TestRelation extends \sfDoctrineRecord
{
    public function setUp()
    {
        $this->hasOne('Test', ['local' => 'test_id', 'foreign' => 'id']);
    }

    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('test_id', 'integer');
    }
}

$column = new \sfDoctrineColumn('name', \Doctrine_Core::getTable('Test'));
$t->is($column->getName(), 'name');
$t->is($column->getFieldName(), 'name');
$t->is($column->getPhpName(), 'name');
$t->is($column->isNotNull(), true);

$column = new \sfDoctrineColumn('test', \Doctrine_Core::getTable('Test'));
$t->is($column->getName(), 'test');
$t->is($column->getFieldName(), 'TEST');
$t->is($column->getPhpName(), 'TEST');

$t->is($column->getDoctrineType(), 'string');
$t->is($column->getType(), 'VARCHAR');
$t->is($column->getLength(), 255);
$t->is($column->getSize(), 255);
$t->is($column->hasDefinitionKey('length'), true);
$t->is($column->getDefinitionKey('type'), 'string');
$t->is($column->isNotNull(), false);

// Is not null and has definition key
$column = new \sfDoctrineColumn('email', \Doctrine_Core::getTable('Test'));
$t->is($column->isNotNull(), true);
$t->is($column->hasDefinitionKey('email'), true);
$t->is($column->getDefinitionKey('email'), true);

// Is primary key
$column = new \sfDoctrineColumn('id', \Doctrine_Core::getTable('Test'));
$t->is($column->isPrimaryKey(), true);

// Relation/foreign key functions
$column = new \sfDoctrineColumn('test_id', \Doctrine_Core::getTable('TestRelation'));
$t->is($column->isForeignKey(), true);
$t->is($column->getForeignClassName(), 'Test');
$t->is($column->getForeignTable()->getOption('name'), 'Test');
$t->is($column->getTable()->getOption('name'), 'TestRelation');

// Array access
$t->is($column['type'], 'integer');
