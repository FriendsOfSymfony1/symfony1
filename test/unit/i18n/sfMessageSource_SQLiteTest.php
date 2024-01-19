<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$plan = 10;
$t = new \lime_test($plan);

if (!extension_loaded('SQLite') && !extension_loaded('pdo_SQLite')) {
    $t->skip('SQLite needed to run these tests', $plan);

    return;
}

// setup
$temp = __DIR__.'/'.rand(11111, 99999);
sf_test_shutdown();

register_shutdown_function('sf_test_shutdown');

function sf_test_shutdown()
{
    global $temp;

    if (file_exists($temp)) {
        unlink($temp);
    }
}

$source = init_fixtures($temp);
$source->setCulture('fr_FR');

// ->loadData()
$t->diag('->loadData()');
$messages = $source->loadData($source->getSource('messages.fr_FR'));
$t->is($messages['an english sentence'][0], 'une phrase en français', '->loadData() loads messages from a SQLite file');

// ->save()
$t->diag('->save()');
$t->is($source->save(), false, '->save() returns false if no message is saved');
$source->append('New message');
$t->is($source->save(), true, '->save() returns true if some messages are saved');
$source = \sfMessageSource::factory('SQLite', 'sqlite://localhost/'.$temp);
$source->setCulture('fr_FR');
$format = new \sfMessageFormat($source);
$t->is($format->format('New message'), 'New message', '->save() saves new messages');

// test new culture
$source->setCulture('it');
$source->append('New message (it)');
$source->save();

$source = \sfMessageSource::factory('SQLite', 'sqlite://localhost/'.$temp);
$source->setCulture('it');
$format = new \sfMessageFormat($source);
$t->is($format->format('New message (it)'), 'New message (it)', '->save() saves new messages');

$source->setCulture('fr_FR');

// ->update()
$t->diag('->update()');
$t->is($source->update('New message', 'Nouveau message', 'Comments'), true, '->update() returns true if the message has been updated');
$source = \sfMessageSource::factory('SQLite', 'sqlite://localhost/'.$temp);
$source->setCulture('fr_FR');
$format = new \sfMessageFormat($source);
$t->is($format->format('New message'), 'Nouveau message', '->update() updates a message translation');

// ->delete()
$t->diag('->delete()');
$t->is($source->delete('Non existant message'), false, '->delete() returns false if the message has not been deleted');
$t->is($source->delete('New message'), true, '->delete() returns true if the message has been deleted');
$source = \sfMessageSource::factory('SQLite', 'sqlite://localhost/'.$temp);
$source->setCulture('fr_FR');
$format = new \sfMessageFormat($source);
$t->is($format->format('New message'), 'New message', '->delete() deletes a message');

function init_fixtures($temp)
{
    $queries = [
        'CREATE TABLE catalogue (cat_id INTEGER PRIMARY KEY, name VARCHAR NOT NULL, source_lang VARCHAR, target_lang VARCHAR, date_created INT, date_modified INT, author VARCHAR);',
        'CREATE TABLE trans_unit (msg_id INTEGER PRIMARY KEY, cat_id INTEGER NOT NULL DEFAULT \'1\', id VARCHAR, source TEXT, target TEXT, comments TEXT, date_added INT, date_modified INT, author VARCHAR, translated INT(1) NOT NULL DEFAULT \'0\');',
        "INSERT INTO catalogue (cat_id, name) VALUES (1, 'messages.fr_FR')",
        "INSERT INTO catalogue (cat_id, name) VALUES (2, 'messages.it')",
        "INSERT INTO trans_unit (msg_id, cat_id, id, source, target, translated) VALUES (1, 1, 1, 'an english sentence', 'une phrase en français', 1)",
        "INSERT INTO trans_unit (msg_id, cat_id, id, source, target, translated) VALUES (2, 1, 2, 'another english sentence', 'une autre phrase en français', 1)",
    ];

    if (version_compare(PHP_VERSION, '5.3', '>')) {
        $db = new \SQLite3($temp);

        foreach ($queries as $query) {
            $db->exec($query);
        }

        $db->close();
    } else {
        $db = sqlite_open($temp);

        foreach ($queries as $query) {
            sqlite_query($query, $db);
        }

        sqlite_close($db);
    }

    return \sfMessageSource::factory('SQLite', 'sqlite://localhost/'.$temp);
}
