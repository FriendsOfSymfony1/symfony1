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

require_once __DIR__.'/../../../lib/helper/TagHelper.php';

require_once __DIR__.'/../../../lib/helper/JavascriptBaseHelper.php';

$t = new \lime_test(9, new \lime_output_color());

// boolean_for_javascript()
$t->diag('boolean_for_javascript()');
$t->is(boolean_for_javascript(true), 'true', 'boolean_for_javascript() makes a javascript representation of the boolean if the param is boolean');
$t->is(boolean_for_javascript(false), 'false', 'boolean_for_javascript() makes a javascript representation of the boolean if the param is boolean');
$t->is(boolean_for_javascript(1 == 0), 'false', 'boolean_for_javascript() makes a javascript representation of the boolean if the param is boolean');
$t->is(boolean_for_javascript('dummy'), 'dummy', 'boolean_for_javascript() makes a javascript representation of the boolean if the param is boolean');

// options_for_javascript()
$t->diag('options_for_javascript()');
$t->is(options_for_javascript(["'a'" => "'b'", "'c'" => false]), "{'a':'b', 'c':false}", 'options_for_javascript() makes a javascript representation of the passed array');
$t->is(options_for_javascript(["'a'" => ["'b'" => "'c'"]]), "{'a':{'b':'c'}}", 'options_for_javascript() works with nested arrays');

// javascript_tag()
$t->diag('javascript_tag()');
$expect = <<<'EOT'
<script type="text/javascript">
//<![CDATA[
alert("foo");
//]]>
</script>
EOT;
$t->is(javascript_tag('alert("foo");'), $expect, 'javascript_tag() takes the content as string parameter');

// link_to_function()
$t->diag('link_to_function()');
$t->is(link_to_function('foo', 'alert(\'bar\')'), '<a href="#" onclick="alert(\'bar\'); return false;">foo</a>', 'link_to_function generates a link with onClick handler for function');
// #4152
$t->is(link_to_function('foo', 'alert(\'bar\')', ['confirm' => 'sure?']), '<a href="#" onclick="if(window.confirm(\'sure?\')){ alert(\'bar\');}; return false;">foo</a>', 'link_to_function works fine with confirm dialog');
