<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(1);

class Foo
{
    public function getid()
    {
        return 1;
    }
}

// simulate Doctrine route
class ObjectRoute extends sfObjectRoute
{
    protected function doConvertObjectToArray($object)
    {
        $parameters = [];
        foreach ($this->getRealVariables() as $variable) {
            if (method_exists($object, $method = 'get'.$variable)) {
                $parameters[$variable] = $object->{$method}();
            }
        }

        return $parameters;
    }
}

// ->generate()
$t->diag('->generate()');
$route = new ObjectRoute('/:id', [], [], ['model' => 'Foo', 'type' => 'object']);
$t->is($route->generate(['sf_subject' => new Foo()]), '/1', '->generate() generates a URL with the given parameters');
