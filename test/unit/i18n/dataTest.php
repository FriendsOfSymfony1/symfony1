<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

$t = new lime_test(92);

$t->diag('i18n data');
$en = unserialize(file_get_contents(__DIR__.'/../../../lib/i18n/data/en.dat'));

// check main keys
foreach (['Countries', 'Currencies', 'Languages', 'LocaleScript', 'NumberPatterns', 'Scripts', 'Types', 'Variants', 'Version', 'calendar', 'zoneStrings'] as $entry) {
    $t->ok(isset($en[$entry]), sprintf('i18n data files may contain a "%s" entry', $entry));
}

// Countries
$t->diag('Countries');
$t->is($en['Countries']['GB'], 'United Kingdom', '"Countries" contains country names');
$t->is($en['Countries']['FR'], 'France', '"Countries" contains country names');

// Currencies
$t->diag('Currencies');
$t->is($en['Currencies']['EUR'], ['€', 'Euro'], '"Currencies" contains currency names and symbols');
$t->is($en['Currencies']['USD'], ['$', 'US Dollar'], '"Currencies" contains currency names and symbols');

// Languages
$t->diag('Languages');
$t->is($en['Languages']['fr'], 'French', '"Languages" contains language names');
$t->is($en['Languages']['en'], 'English', '"Languages" contains language names');

// NumberPatterns
$t->diag('NumberPatterns');
$t->is($en['NumberPatterns'][0], '#,##0.###', '"NumberPatterns" contains patterns to format numbers');
$t->is($en['NumberPatterns'][1], '¤#,##0.00;(¤#,##0.00)', '"NumberPatterns" contains patterns to format numbers');
$t->is($en['NumberPatterns'][2], '#,##0%', '"NumberPatterns" contains patterns to format numbers');
$t->is($en['NumberPatterns'][3], '#E0', '"NumberPatterns" contains patterns to format numbers');

// calendar
$t->diag('calendar');
$c = $en['calendar']['gregorian'];

$t->diag('calendar/DateTimePatterns');
$t->is($c['DateTimePatterns'][0], 'h:mm:ss a zzzz', '"calendar" contains date time patterns');
$t->is($c['DateTimePatterns'][1], 'h:mm:ss a z', '"calendar" contains date time patterns');
$t->is($c['DateTimePatterns'][2], 'h:mm:ss a', '"calendar" contains date time patterns');
$t->is($c['DateTimePatterns'][3], 'h:mm a', '"calendar" contains date time patterns');
$t->is($c['DateTimePatterns'][4], 'EEEE, MMMM d, y', '"calendar" contains date time patterns');
$t->is($c['DateTimePatterns'][5], 'MMMM d, y', '"calendar" contains date time patterns');
$t->is($c['DateTimePatterns'][6], 'MMM d, y', '"calendar" contains date time patterns');
$t->is($c['DateTimePatterns'][7], 'M/d/yy', '"calendar" contains date time patterns');
$t->is($c['DateTimePatterns'][8], '{1} {0}', '"calendar" contains date time patterns');

$t->diag('calendar/dayNames');
$a = $c['dayNames']['format']['abbreviated'];
foreach ([0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'] as $key => $name) {
    $t->is($a[$key], $name, '"calendar" contains abrreviated day names');
}
$a = $c['dayNames']['stand-alone']['narrow'];
foreach ([0 => 'S', 1 => 'M', 2 => 'T', 3 => 'W', 4 => 'T', 5 => 'F', 6 => 'S'] as $key => $name) {
    $t->is($a[$key], $name, '"calendar" contains narrow day names');
}
$a = $c['dayNames']['format']['wide'];
foreach ([0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'] as $key => $name) {
    $t->is($a[$key], $name, '"calendar" contains day names');
}

$t->diag('calendar/eras');
$t->is($c['eras']['abbreviated'], [0 => 'BC', 1 => 'AD'], '"calendar" contains era names');

$t->diag('calendar/monthNames');
$a = $c['monthNames']['format']['abbreviated'];
foreach ([0 => 'Jan', 1 => 'Feb', 2 => 'Mar', 3 => 'Apr', 4 => 'May', 5 => 'Jun', 6 => 'Jul', 7 => 'Aug', 8 => 'Sep', 9 => 'Oct', 10 => 'Nov', 11 => 'Dec'] as $key => $name) {
    $t->is($a[$key], $name, '"calendar" contains abrreviated month names');
}
$a = $c['monthNames']['stand-alone']['narrow'];
foreach ([0 => 'J', 1 => 'F', 2 => 'M', 3 => 'A', 4 => 'M', 5 => 'J', 6 => 'J', 7 => 'A', 8 => 'S', 9 => 'O', 10 => 'N', 11 => 'D'] as $key => $name) {
    $t->is($a[$key], $name, '"calendar" contains narrow month names');
}
$a = $c['monthNames']['format']['wide'];
foreach ([0 => 'January', 1 => 'February', 2 => 'March', 3 => 'April', 4 => 'May', 5 => 'June', 6 => 'July', 7 => 'August', 8 => 'September', 9 => 'October', 10 => 'November', 11 => 'December'] as $key => $name) {
    $t->is($a[$key], $name, '"calendar" contains month names');
}

// zoneStrings
$t->diag('zoneStrings');
$a = $en['zoneStrings']['meta:Alaska'];
foreach (['ld' => 'Alaska Daylight Time', 'lg' => 'Alaska Time', 'ls' => 'Alaska Standard Time', 'sg' => 'AKT'] as $key => $name) {
    $t->is($a[$key], $name, '"zoneStrings" contains time zone names');
}

// Types

// LocaleScript

// Scripts

// Variants
