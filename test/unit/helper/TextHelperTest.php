<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

require_once(dirname(__FILE__).'/../../../lib/helper/TagHelper.php');
require_once(dirname(__FILE__).'/../../../lib/helper/TextHelper.php');

$t = new lime_test(73);

// truncate_text()
$t->diag('truncate_text()');
$t->is(truncate_text(''), '', 'truncate_text() does nothing on an empty string');
$t->is(truncate_text('Test test test test test test test test test'), 'Test test test test test te...', 'truncate_text() truncates to 30 characters by default');

$text = str_repeat('A', 35);
$truncated = str_repeat('A', 27).'...';
$t->is(truncate_text($text), $truncated, 'truncate_text() adds ... to the truncated text');

$text = str_repeat('A', 35);
$truncated = str_repeat('A', 22).'...';
$t->is(truncate_text($text, 25), $truncated, 'truncate_text() takes the truncate length as its second argument');

$text = str_repeat('A', 35);
$truncated = str_repeat('A', 21).'BBBB';
$t->is(truncate_text($text, 25, 'BBBB'), $truncated, 'truncate_text() takes the pad text as its third argument');
$t->is(truncate_text('Test1 test2', 6), 'Tes...', 'truncate_text() includes the length of the pad text in the total length');
$t->is(truncate_text('Short test', 10), 'Short test', 'truncate_text() returns the text unchanged if the truncate length is longer than the text. No pad text is added.');

$text = 'Testing testing testing testing';
$truncated_false = 'Testing testi...';
$truncated_true = 'Testing...';
$t->is(truncate_text($text, 16, '...', false), $truncated_false, 'truncate_text() accepts a lastspace boolean as its fourth argument');
$t->is(truncate_text($text, 16, '...', true), $truncated_true, 'truncate_text() with lastspace=true truncates text on the last whitespace found before truncate_length is reached, and then appends pad. Final results may be shorter than truncate_length.');

$text = 'Web. applications. spend. a. large. share.';
$t->is(truncate_text($text, 15, '[...]', false, '/[.]\s+?/'), 'Web. [...]', 'truncate_text() accepts a regex pattern as its fifth argument. If pattern is not found, truncate_text() will truncate to truncate_length');
$t->is(truncate_text($text, 15, '[...]', false, '/[.]\s+?/', 0), 'Web. applications. [...]', 'truncate_text() accepts an max length as its sixth argument if pattern argument is provided');
$t->is(truncate_text($text, 15, '[...]', false, '/[.]\s+?/', 5), 'Web. [...]', 'truncate_text(), if length_max is not given or is less than truncate_length, truncates on first pattern found');
$t->is(truncate_text($text, 15, '[...]', false, '/[.]\s+?/', 22), 'Web. applications. [...]', 'truncate_text(), if length_max=0 or is greater than truncate_length, truncates on pattern found after truncate_length and before length_max');
$t->is(truncate_text($text, 15, '[...]', true, '/[.]\s+?/', 0), 'Web. applications. [...]', 'truncate_text() ignores value of truncate_lastspace if pattern is set.');

if (extension_loaded('mbstring'))
{
  $oldEncoding = mb_internal_encoding();
  $t->is(truncate_text('のビヘイビアにパラメーターを渡すことで特定のモデルでのフォーム生成を無効にできます', 11), 'のビヘイビアにパ...', 'truncate_text() handles unicode characters using mbstring if available');
  $t->is(mb_internal_encoding(), $oldEncoding, 'truncate_text() sets back the internal encoding in case it changes it');
}
else
{
  $t->skip('mbstring extension is not enabled', 2);
}

// highlight_text()
$t->diag('highlight_text()');
$t->is(highlight_text("This is a beautiful morning", "BEAUTIFUL"),
  "This is a <strong class=\"highlight\">beautiful</strong> morning",
  'highlight_text() highlights a phrase given as its second argument and is case-insensitive'
);

$t->is(highlight_text("This is a most beautiful morning", "most beautiful"),
  "This is a <strong class=\"highlight\">most beautiful</strong> morning",
  'highlight_text() highlights the entire phrase given as its second argument'
);

$t->is(highlight_text("This is a most beautiful mid afternoon", array('a most', 'mid afternoon')),
  "This is <strong class=\"highlight\">a most</strong> beautiful <strong class=\"highlight\">mid afternoon</strong>",
  'highlight_text() accepts an array of phrases as its second argument'
);

$t->is(highlight_text("This is a beautiful morning, but also a beautiful day", "beautiful"),
  "This is a <strong class=\"highlight\">beautiful</strong> morning, but also a <strong class=\"highlight\">beautiful</strong> day",
  'highlight_text() highlights all occurrences of a phrase given as its second argument'
);

$t->is(highlight_text("This is a beautiful morning, but also a beautiful day", "beautiful", '<b>\\1</b>'),
  "This is a <b>beautiful</b> morning, but also a <b>beautiful</b> day",
  'highlight_text() takes a highlight pattern as its third argument'
);

$t->is(highlight_text('', 'beautiful'), '', 'highlight_text() returns an empty string if input text is empty');
$t->is(highlight_text('', ''), '', 'highlight_text() returns an empty string if input text is empty');
$t->is(highlight_text('foobar', 'beautiful'), 'foobar', 'highlight_text() returns the input text unchanged if the phrase to highlight is not found');
$t->is(highlight_text('foobar', ''), 'foobar', 'highlight_text() returns the input text unchanged if the phrase to highlight empty');

$t->is(highlight_text("This is a beautiful! morning", "beautiful!"), "This is a <strong class=\"highlight\">beautiful!</strong> morning", 'highlight_text() escapes search string to be safe in a regex');
$t->is(highlight_text("This is a beautiful! morning", "beautiful! morning"), "This is a <strong class=\"highlight\">beautiful! morning</strong>", 'highlight_text() escapes search string to be safe in a regex');
$t->is(highlight_text("This is a beautiful? morning", "beautiful? morning"), "This is a <strong class=\"highlight\">beautiful? morning</strong>", 'highlight_text() escapes search string to be safe in a regex');

$t->is(highlight_text("The http://www.google.com/ website is great", "http://www.google.com/"), "The <strong class=\"highlight\">http://www.google.com/</strong> website is great", 'highlight_text() escapes search string to be safe in a regex');

// excerpt_text()
$t->diag('excerpt_text()');
$t->is(excerpt_text('', 'foo', 5), '', 'excerpt_text() returns an empty string if the input text is empty');
$t->is(excerpt_text('foo', '', 5), '', 'excerpt_text() returns an empty string if the second argument, case-insensitive phrase is empty');
$t->is(excerpt_text("This is a beautiful morning", "day"), '', 'excerpt_text() returns an empty string if the phrase is not found in the text');
$t->is(excerpt_text("This is a beautiful morning", "BEAUTIFUL", 5), "...is a beautiful morn...", 'excerpt_text() takes a radius as its third argument.');
$t->is(excerpt_text("This is a beautiful morning", "this", 5), "This is a...", 'excerpt_text() leaves a maximum of radius number of chars on either side of excerpted phrase.');
$t->is(excerpt_text("This is a beautiful morning", "morning", 5), "...iful morning", 'excerpt_text() leaves a maximum of radius number of chars on either side of excerpted phrase.');
$t->is(excerpt_text("This is a beautiful morning", "beautiful", 2, '****'), "****a beautiful m****", 'excerpt_text() takes a fourth argument of a custom excerpt pad.');
$t->is(excerpt_text("This is a beautiful morning", "morning", 5, '...', true), "... morning", 'excerpt_text() takes a fifth argument allowing excerpt on whitespace');
$t->is(excerpt_text("This is a beautiful mid morning", "ful", 10, '...', true), "... a beautiful mid ...", 'excerpt_text() takes a fifth argument allowing excerpt on whitespace');
$t->is(excerpt_text("This is a beautiful morning", "This", 5, '...', true), "This is ...", 'excerpt_text() takes a fifth argument allowing excerpt on whitespace');

// wrap_text()
$t->diag('wrap_text()');
$line = 'This is a very long line to be wrapped...';
$t->is(wrap_text($line), "This is a very long line to be wrapped...\n", 'wrap_text() wraps long lines with a default of 80');
$t->is(wrap_text($line, 10), "This is a\nvery long\nline to be\nwrapped...\n", 'wrap_text() takes a line length as its second argument');
$t->is(wrap_text($line, 5), "This\nis a\nvery\nlong\nline\nto be\nwrapped...\n", 'wrap_text() takes a line length as its second argument');

// simple_format_text()
$t->diag('simple_format_text()');
$t->is(simple_format_text("crazy\r\n cross\r platform linebreaks"), "<p>crazy\n<br /> cross\n<br /> platform linebreaks</p>", 'simple_format_text() replaces \n by <br />');
$t->is(simple_format_text("A paragraph\n\nand another one!"), "<p>A paragraph</p><p>and another one!</p>", 'simple_format_text() replaces \n\n by <p>');
$t->is(simple_format_text("A paragraph\n\n\n\nand another one!"), "<p>A paragraph</p><p>and another one!</p>", 'simple_format_text() replaces \n\n\n\n by <p>');
$t->is(simple_format_text("A paragraph\n With a newline"), "<p>A paragraph\n<br /> With a newline</p>", 'simple_format_text() wrap all string with <p>');
$t->is(simple_format_text("1\n2\n3"), "<p>1\n<br />2\n<br />3</p>", 'simple_format_text() Ticket #6824');
$t->is(simple_format_text("A paragraph\n With a newline", array('class' => 'my_class', 'title' => 'My Title')), "<p class=\"my_class\" title=\"My Title\">A paragraph\n<br /> With a newline</p>", 'simple_format_text() accepts an array of html options to be applied to paragraph tag.');

// strip_links_text()
$t->diag('strip_links_text()');
$t->is(strip_links_text('<a href="almost">on my mind</a> and <a href="mailto:me@dot.com">email me</a>'), 'on my mind and email me', 'strip_links_text() strips all links in input');
$t->is(strip_links_text('<a href="first.html">first</a> and <a href="second.html">second</a>'), "first and second", 'strip_links_text() strips all links in input');

// auto_link_text()
$t->diag('auto_link_text()');
$email_raw = 'fabien.potencier@symfony-project.com';
$email_result = '<a href="mailto:'.$email_raw.'">'.$email_raw.'</a>';
$email2_raw = 'user.локал@utf8-локалхост.локал';
$email2_result = '<a href="mailto:'.$email2_raw.'" title="Email Me!">'.$email2_raw.'</a>';
$email3_raw = 'myemail@dept.example.com';
$email3_result = '<a href="mailto:'.$email3_raw.'" class="my_class">'.$email3_raw.'</a>';
$link_raw = 'http://www.google.com';
$link_result = '<a href="'.$link_raw.'">'.$link_raw.'</a>';
$link2_raw = 'www.google.com';
$link2_result = '<a href="http://'.$link2_raw.'">'.$link2_raw.'</a>';
$link3_raw = 'https://www.google.com';
$link3_result = '<a href="'.$link3_raw.'">'.$link3_raw.'</a>';
$link4_raw = 'news.yahoo.com';

$t->is(auto_link_text('Go to '.$link_raw.' and say hello to '.$email_raw), 'Go to '.$link_result.' and say hello to '.$email_result, 'auto_link_text() converts both emails and URLs to links if no second argument is given');
$t->is(auto_link_text('hello '.$email_raw, 'email_addresses'), 'hello '.$email_result, 'auto_link_text() accepts a second argument to specify what to link: urls, email_addresses, or all');
$t->is(auto_link_text('Go to '.$link_raw, 'urls'), 'Go to '.$link_result, 'auto_link_text() converts URLs without http to links, if they start with www.');
$t->is(auto_link_text('Go to '.$link3_raw, 'urls'), 'Go to '.$link3_result, 'auto_link_text() converts URLs with https to links.');
$t->is(auto_link_text('Go to '.$link4_raw, 'urls'), 'Go to '.$link4_raw , 'auto_link_text() will not convert URLs to links if they do not start with http, https, or www.');
$t->is(auto_link_text('Go to '.$link_raw, 'email_addresses'), 'Go to '.$link_raw, 'auto_link_text() does not convert URLs if email_addresses is given as the second argument');
$t->is(auto_link_text('<p>Link '.$link_raw.'</p>'), '<p>Link '.$link_result.'</p>', 'auto_link_text() converts URLs within html to links');
$t->is(auto_link_text('<p>http://www.google.com/?q=symfony Link</p>'), '<p><a href="http://www.google.com/?q=symfony">http://www.google.com/?q=symfony</a> Link</p>', 'auto_link_text() converts URLs with query params to links');
$t->is(auto_link_text('<p>http://www.google.com/ Link</p>', 'urls', array('title' => 'Google It!')), '<p><a href="http://www.google.com/" title="Google It!">http://www.google.com/</a> Link</p>', 'auto_link_text() accepts an array of html options as its third argument');
$t->is(auto_link_text('<p>http://www.google.com/?q=symfony+link</p>', 'all', array(), true), '<p><a href="http://www.google.com/?q=symfony+link">http://www.google.com/?q=symfo...</a></p>', 'auto_link_text() truncates long URLs to default 30 chars if the fourth argument is set to true');
$t->is(auto_link_text('<p>http://www.google.com/?q=symfony+link</p>', 'all', array(), true, 20), '<p><a href="http://www.google.com/?q=symfony+link">http://www.google.co...</a></p>', 'auto_link_text() truncates long URLs to the length set as the fifth argument if the fourth argument is set to true');
$t->is(auto_link_text('<p>http://www.google.com/?q=symfony+link</p>', 'all', array(), true, 20, '***'), '<p><a href="http://www.google.com/?q=symfony+link">http://www.google.co***</a></p>', 'auto_link_text() accepts a custom truncation padding string as its sixth argument');
$t->is(auto_link_text('<p>http://twitter.com/#!/fabpot</p>'),'<p><a href="http://twitter.com/#!/fabpot">http://twitter.com/#!/fabpot</a></p>',"auto_link_text() converts URLs with complex fragments to links");
$t->is(auto_link_text('<p>http://twitter.com/#!/fabpot is Fabien Potencier on Twitter</p>'),'<p><a href="http://twitter.com/#!/fabpot">http://twitter.com/#!/fabpot</a> is Fabien Potencier on Twitter</p>',"auto_link_text() converts URLs with complex fragments and trailing text to links");
$t->is(auto_link_text('hello '.$email_result, 'email_addresses'), 'hello '.$email_result, "auto_link_text() does not double-link emails");
$t->is(auto_link_text('<p>Link '.$link_result.'</p>'), '<p>Link '.$link_result.'</p>', "auto_link_text() does not double-link URLs");
$t->is(auto_link_text('<div>text w/o trailing space</div>'.$email_raw, 'email_addresses'), '<div>text w/o trailing space</div>'.$email_result, 'auto_link_text() converts emails at the beginning of lines to links');
$t->is(auto_link_text('http://root@localhost.local', 'email_addresses'), 'http://root@localhost.local', 'auto_link_text() does not link text with @ symbol if text begins with http:');
$t->is(auto_link_text('https://root@localhost.local', 'email_addresses'), 'https://root@localhost.local', 'auto_link_text() does not link text with @ symbol if text begins with https:');
$t->is(auto_link_text('Fabien <'.$email_raw.'>', 'email_addresses'), 'Fabien <'.$email_result.'>', 'auto_link_text() converts emails within angle brackets to links');
$t->is(auto_link_text($email3_raw, 'email_addresses', array('class' => 'my_class')), $email3_result, 'auto_link_text() converts emails with complex hostnames to links and accepts an array of html options as its third argument');
$t->is(auto_link_text($email2_raw, 'all', array('title' => 'Email Me!'), false, 30, '...', true), $email2_result, 'auto_link_text() converts unicode emails to links if the seventh argument (is_unicode) is set to true');
$t->is(auto_link_text($email3_result, 'email_addresses'), $email3_result, 'auto_link_text() will not convert unicode emails to links if the seventh argument (is_unicode) is not set to true');
