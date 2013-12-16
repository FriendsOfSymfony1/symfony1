<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2004 David Heinemeier Hansson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * TextHelper.
 *
 * @package    symfony
 * @subpackage helper
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     David Heinemeier Hansson
 * @version    SVN: $Id$
 */

/**
 * Truncates text.
 *
 * @param string $text The incoming text.
 * @param integer $truncate_length Default: 30. The length of returned string.
 *  This value may be ignored with some values of *truncate_pattern* and *length_max*
 * @param string $truncate_pad Default: '...'. Appended to returned string.
 *  Length of pad is included in returned string length.
 * @param bool $truncate_lastspace Default: false. If true, truncates *text* on the last whitespace found,
 *  if any, before *truncate_length* is reached, and then appends pad.
 *  Returned string length may be shorter than *truncate_length* if true.
 * @param string $truncate_pattern Default: null. Regex pattern to define where to break *text* for truncation.
 *  If *truncate_pattern* is not found, *text* is truncated to *truncate_length*
 *  N.b.: *truncate_lastspace* value is ignored if *truncate_pattern* is set.
 * @param integer $length_max Default: null. The max returned string length. Use with *truncate_pattern*.
 *  - If *length_max* is not given or is < *truncate_length*, *text* will break on the
 *    first *truncate_pattern* found. Returned string length may be shorter than *truncate_length*.
 *  - If *length_max* = 0 or is > *truncate_length*, *text* will break on the
 *    first *truncate_pattern* found between *truncate_length* and *length_max*.
 * @return string
 */
function truncate_text($text, $truncate_length = 30, $truncate_pad = '...', $truncate_lastspace = false, $truncate_pattern = null, $length_max = null)
{
  if (empty($text))
  {
    return '';
  }

  $mbstring = extension_loaded('mbstring');
  if ($mbstring)
  {
    $old_encoding = mb_internal_encoding();
    @mb_internal_encoding(mb_detect_encoding($text));
  }
  $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
  $substr = ($mbstring) ? 'mb_substr' : 'substr';

  if ($strlen($text) > $truncate_length)
  {
    if ($truncate_pattern)
    {
      $length_min = null !== $length_max && (0 == $length_max || $length_max > $truncate_length) ? $truncate_length : null;

      preg_match($truncate_pattern, $text, $matches, PREG_OFFSET_CAPTURE, $length_min);

      if ($matches)
      {
        if ($length_min)
        {
          $truncate_pad = $matches[0][0].$truncate_pad;
          $truncate_length = $matches[0][1] + $strlen($truncate_pad);
        }
        else
        {
          $match = end($matches);
          $truncate_pad = $match[0].$truncate_pad;
          $truncate_length = $match[1] + $strlen($truncate_pad);
        }
      }
    }

    $truncate_text = $substr($text, 0, $truncate_length - $strlen($truncate_pad));
    // ignore truncate_lastspace if truncate_pattern is set
    if ($truncate_lastspace && !$truncate_pattern)
    {
      $truncate_text = preg_replace('/\s+?(\S+)?$/', '', $truncate_text);
    }
    $text = $truncate_text.$truncate_pad;
  }

  if ($mbstring)
  {
    @mb_internal_encoding($old_encoding);
  }

  return $text;
}

/**
 * Highlights *phrase* where it is found in *text* by surrounding it with *highlighter*.
 *
 * Example: <strong class="highlight">I'm a highlighted phrase.</strong>.
 * N.b.: The *phrase* is sanitized to include only letters, digits, and spaces before use.
 *
 * @param string $text The incoming text.
 * @param mixed $phrase Case-insensitive string, array or sfOutputEscaperArrayDecorator instance of words to highlight
 * @param string $highlighter Default: '<strong class="highlight">\\1</strong>'. Regex replacement input to preg_replace.
 *  Pass a single-quoted string with \\1 where the *phrase* is to be inserted.
 *
 * @return string
 */
function highlight_text($text, $phrase, $highlighter = '<strong class="highlight">\\1</strong>')
{
  if (empty($text))
  {
    return '';
  }

  if (empty($phrase))
  {
    return $text;
  }

  if (is_array($phrase) or ($phrase instanceof sfOutputEscaperArrayDecorator))
  {
    foreach ($phrase as $word)
    {
      $pattern[] = '/('.preg_quote($word, '/').')/i';
      $replacement[] = $highlighter;
    }
  }
  else
  {
    $pattern = '/('.preg_quote($phrase, '/').')/i';
    $replacement = $highlighter;
  }

  return preg_replace($pattern, $replacement, $text);
}

/**
 * Extracts an excerpt from *text*.
 *
 * Surrounds *phrase* found within *text* with *radius* number of chars.
 * Example: excerpt("hello my world", "my", 3) => "...lo my wo...".
 *
 * @param string $text The incoming text.
 * @param string $phrase Case-insensitive phrase to excerpt.
 *  N.b.: If the phrase isn't found, an empty string is returned.
 * @param integer $radius Default: 100. Number of chars on either side of excerpted *phrase*.
 * @param string $excerpt_pad Default: '...'. The string to prepend and append to the excerpted *phrase*.
 * @param bool $excerpt_space Default: false. If true, text will be broken on only whitespace, not in the middle of words.
 *  This might return a smaller *radius* than specified.
 *  Example: excerpt("hello my world", "my", 3, "...", true) => "... my ..."
 *
 * @return string
 */
function excerpt_text($text, $phrase, $radius = 100, $excerpt_pad = '...', $excerpt_space = false)
{
  if (empty($text) || empty($phrase))
  {
    return '';
  }

  $mbstring = extension_loaded('mbstring');
  if ($mbstring)
  {
    $old_encoding = mb_internal_encoding();
    @mb_internal_encoding(mb_detect_encoding($text));
  }
  $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
  $strpos = ($mbstring) ? 'mb_strpos' : 'strpos';
  $strtolower = ($mbstring) ? 'mb_strtolower' : 'strtolower';
  $substr = ($mbstring) ? 'mb_substr' : 'substr';

  $found_pos = $strpos($strtolower($text), $strtolower($phrase));
  $return_string = '';
  if ($found_pos !== false)
  {
    $start_pos = max($found_pos - $radius, 0);
    $end_pos = min($found_pos + $strlen($phrase) + $radius, $strlen($text));
    $excerpt = $substr($text, $start_pos, $end_pos - $start_pos);
    $prefix = ($start_pos > 0) ? $excerpt_pad : '';
    $postfix = $end_pos < $strlen($text) ? $excerpt_pad : '';

    if ($excerpt_space)
    {
      // only cut off at ends where $excerpt_string is added
      if ($prefix)
      {
        $excerpt = preg_replace('/^(\S+)?\s+?/', ' ', $excerpt);
      }
      if ($postfix)
      {
        $excerpt = preg_replace('/\s+?(\S+)?$/', ' ', $excerpt);
      }
    }

    $return_string = $prefix.$excerpt.$postfix;
  }

  if ($mbstring)
  {
    @mb_internal_encoding($old_encoding);
  }
  return $return_string;
}

/**
 * Wrap long lines to specified length.
 *
 * @param string $text The incoming text.
 * @param integer $line_length Default: 80. The length to wrap the lines.
 *
 * @return string
 */
function wrap_text($text, $line_length = 80)
{
  return preg_replace('/(.{1,'.$line_length.'})(\s+|$)/s', "\\1\n", preg_replace("/\n/", "\n\n", $text));
}

/**
 * Returns *text* transformed into html using very simple formatting rules.
 *
 * Surrounds paragraphs with <tt>&lt;p&gt;</tt> tags, and converts line breaks into <tt>&lt;br /&gt;</tt>.
 * Two consecutive newlines(<tt>\n\n</tt>) are considered as a paragraph, one newline (<tt>\n</tt>) is
 * considered a linebreak, three or more consecutive newlines are turned into two newlines.
 *
 * @param string $text The incoming text.
 * @param array $html_options Default: array(). Html options for paragraphs.
 *
 * @return string
 */
function simple_format_text($text, $html_options = array())
{
  $html_options = _tag_options($html_options);

  $text = sfToolkit::pregtr($text, array("/(\r\n|\r)/"        => "\n",               //  Make the newlines crossplatform
                                         "/\n{2,}/"           => "</p><p$html_options>")); // Make two and more newlines into paragraph

  // Make single newline into <br/>
  $text = str_replace("\n", "\n<br />", $text);
  return '<p'.$html_options.'>'.$text.'</p>'; // Wrap the first and last line in paragraph tags
}

/**
 * Removes links from text.
 *
 * Examples: strip_links_text('<a href="http://www.google.com">Google</a>') => Google
 *  strip_links_text('<a href="mailto:me@dot.com">Email Me.</a>') => Email Me.
 *
 * @param string $text The incoming text.
 *
 * @return string
 */
function strip_links_text($text)
{
  return preg_replace('/<a[^>]*>(.*?)<\/a>/s', '\\1', $text);
}

/**
 * Makes urls and email addresses into clickable links.
 *
 * Example:
 *   auto_link("Go to http://www.symfony-project.com and say hello to fabien.potencier@example.com.") =>
 *     Go to <a href="http://www.symfony-project.com">http://www.symfony-project.com</a> and
 *     say hello to <a href="mailto:fabien.potencier@example.com">fabien.potencier@example.com</a>.
 *
 * @param string $text The incoming text.
 * @param string $link Default: 'all'. Define what should be linked. Options are 'all', 'email_addresses' and 'urls'.
 * @param array $html_options Default: array(). Html options for URL and email links.
 * @param bool $truncate Default: false. Truncate URLs or not. Does not apply to email addresses.
 * @param integer $truncate_length Default: 30. Truncation length. Used only if *truncate* is true.
 * @param string $truncate_pad Default: '...'. The string to append to truncated text. Used only if *truncate* is true.
 * @param bool $is_unicode Default: false. If true, allows the inclusion of unicode characters in email addresses.
 *
 * @return string
 */
function auto_link_text($text, $link = 'all', $html_options = array(), $truncate = false, $truncate_length = 30, $truncate_pad = '...', $is_unicode = false)
{
  if ($link == 'all')
  {
    return _auto_link_urls(_auto_link_email_addresses($text, $html_options, $is_unicode), $html_options, $truncate, $truncate_length, $truncate_pad);
  }
  else if ($link == 'email_addresses')
  {
    return _auto_link_email_addresses($text, $html_options, $is_unicode);
  }
  else if ($link == 'urls')
  {
    return _auto_link_urls($text, $html_options, $truncate, $truncate_length, $truncate_pad);
  }
}

/**
 * Makes URLs into clickable links. Ignores URLs that are already linked.
 *
 * @param string $text The incoming text.
 * @param array $html_options Default: array(). Html options for the links.
 * @param bool $truncate Default: false. Truncate link or not.
 * @param integer $truncate_length Default: 30. Truncate length. Used only if *truncate* is true.
 * @param string $truncate_pad Default: '...'. The string to append to truncated text. Used only if *truncate* is true.
 *
 * @return string
 */
function _auto_link_urls($text, $html_options = array(), $truncate = false, $truncate_length = 30, $truncate_pad = '...')
{
  $html_options = _tag_options($html_options);

  $callback_function = '
    if (preg_match("/<a\s/i", $matches[1]))
    {
      return $matches[0];
    }
    ';

  if ($truncate)
  {
    $callback_function .= '
      else if (strlen($matches[2].$matches[3]) > '.$truncate_length.')
      {
        return $matches[1].\'<a href="\'.($matches[2] == "www." ? "http://www." : $matches[2]).$matches[3].\'"'.$html_options.'>\'.substr($matches[2].$matches[3], 0, '.$truncate_length.').\''.$truncate_pad.'</a>\'.$matches[4];
      }
      ';
  }

  $callback_function .= '
    else
    {
      return $matches[1].\'<a href="\'.($matches[2] == "www." ? "http://www." : $matches[2]).$matches[3].\'"'.$html_options.'>\'.$matches[2].$matches[3].\'</a>\'.$matches[4];
    }
    ';

  return preg_replace_callback(
    SF_AUTO_LINK_RE,
    create_function('$matches', $callback_function),
    $text
    );
}

if (!defined('SF_AUTO_LINK_RE'))
{
  define('SF_AUTO_LINK_RE', '~
    (                       # leading text
      <\w+.*?>|             # leading HTML tag, or
      [^=!:\'"/]|           # leading punctuation, or
      ^                     # beginning of line
    )
    (
      (?:https?://)|        # protocol spec, or
      (?:www\.)             # www.*
    )
    (
      [-\w]+                   # subdomain or domain
      (?:\.[-\w]+)*            # remaining subdomains or domain
      (?::\d+)?                # port
      (?:/(?:(?:[\~\w\+%-]|(?:[,.;:][^\s$]))+)?)* # path
      (?:\?[\w\+%&=.;-]+)?     # query string
      (?:\#[\w\-/\?!=]*)?      # trailing anchor
    )
    ([[:punct:]]|\s|<|$)       # trailing text
   ~x');
}

/**
 * Makes email addresses into clickable links. Ignores email addresses that are already linked.
 *
 * @param string $text The incoming text.
 * @param array $html_options Default: array(). Html options for the links.
 * @param bool $is_unicode Default: false. If true, allows the inclusion of unicode characters in email addresses.
 *
 * @return string
 */
function _auto_link_email_addresses($text, $html_options = array(), $is_unicode = false)
{
  if (false === strpos($text, '@'))
  {
    return $text;
  }

  $html_options = _tag_options($html_options);

  /*
    Allowed username characters: upper & lowercase letters and digits 0-9
      and special characters: ! # $ % & ' * + - / = ? ^ _ ` { } | ~
    Other special characters are allowed with restrictions. They are:
      Space and "(),:;<>@[\] (ASCII: 32, 34, 40, 41, 44, 58, 59, 60, 62, 64, 91-93)
      The restrictions for these special characters are that they must only be used when contained between quotation marks,
      and that 2 of them (the backslash \ and quotation mark " (ASCII: 92, 34)) must also be preceded by a backslash \ (e.g. "\\\"").
     Source: http://en.wikipedia.org/wiki/Email_address
  */
  $allowedUsernameChars = ($is_unicode ? '\p{L}\p{Nd}' : '\w'); // '\w' = [a-zA-Z0-9\_] (letters, digits, underscore)

  $allowedUsernameChars .= '\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\}\|\~\.'; // extended allowed username chars

  /*
    Allowed hostname characters: letters, digits 0-9 and a dash
      'words' (labels) separated by a dot
  */
  $allowedHostnameChars = 'a-zA-Z0-9\-\.';

  $allowedHostnameChars .= ($is_unicode ? '\p{L}\p{Nd}' : null);

  return preg_replace('~
    (?<!mailto:) # no previous mailto:
    (?<!http:|https:) # no previous http: or https:
    (?<!['.$allowedUsernameChars.']) # no previous valid email chars
    (['.$allowedUsernameChars.']+) # valid username chars
    @
    (['.$allowedHostnameChars.']+) # valid hostname chars
    (?!['.$allowedHostnameChars.']) # no following valid hostname chars
    (?!</a>) # no trailing </a>
    ~ixu',
    '<a href="mailto:\1@\2"'.$html_options.'>\1@\2</a>',
    $text);
}
