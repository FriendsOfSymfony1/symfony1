<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(8);

// ->__construct()
$t->diag('->__construct()');

$route = new sfRequestRoute('/');
$requirements = $route->getRequirements();
$t->is_deeply($requirements['sf_method'], ['get', 'head'], '->__construct() applies a default "sf_method" requirement of GET or HEAD');

$route = new sfRequestRoute('/', [], ['sf_method' => ['post']]);
$requirements = $route->getRequirements();
$t->is_deeply($requirements['sf_method'], ['post'], '->__construct() does not apply a default "sf_method" requirement if one is already set');

$route = new sfRequestRoute('/', [], ['sf_method' => 'get']);
$requirements = $route->getRequirements();
$t->is_deeply($requirements['sf_method'], ['get'], '->__construct() converts a string "sf_method" requirement to an array');

// ->matchesParameters()
$t->diag('->matchesParameters()');

$route = new sfRequestRoute('/', [], ['sf_method' => ['get', 'head']]);
$t->ok($route->matchesParameters(['sf_method' => 'get']), '->matchesParameters() matches the "sf_method" parameter');

$route = new sfRequestRoute('/', [], ['sf_method' => ['get']]);
$t->ok($route->matchesParameters(['sf_method' => 'GET']), '->matchesParameters() checks "sf_method" requirement case-insensitively');

$route = new sfRequestRoute('/', [], ['sf_method' => ['GET']]);
$t->ok($route->matchesParameters(['sf_method' => 'get']), '->matchesParameters() checks "sf_method" requirement case-insensitively');

// ->matchesUrl()
$t->diag('->matchesUrl()');

$route = new sfRequestRoute('/', [], ['sf_method' => 'GET']);
$t->isa_ok($route->matchesUrl('/', ['method' => 'get']), 'array', '->matchesUrl() check "sf_method" requirement case-insensitively');

$route = new sfRequestRoute('/', [], ['sf_method' => 'get']);
$t->isa_ok($route->matchesUrl('/', ['method' => 'GET']), 'array', '->matchesUrl() check "sf_method" requirement case-insensitively');
