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
 * Truncates +text+ to the length of +length+ and replaces the last three characters with the +truncate_string+
 * if the +text+ is longer than +length+.
 *
 * @param string $text The original text
 * @param integer $length The length for truncate
 * @param string $truncate_string The string to add after truncated text
 * @param bool $truncate_lastspace Remove or not last space after truncate
 * @param string $truncate_pattern Pattern
 * @param integer $length_max Used only with truncate_pattern
 *
 * @return string
 */
function truncate_text($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false, $truncate_pattern = null, $length_max = null)
{
  if ('' == $text)
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

  if ($strlen($text) > $length)
  {
    if ($truncate_pattern)
    {
      $length_min = null !== $length_max && (0 == $length_max || $length_max > $length) ? $length : null;

      preg_match($truncate_pattern, $text, $matches, PREG_OFFSET_CAPTURE, $length_min);

      if ($matches)
      {
        if ($length_min)
        {
          $truncate_string = $matches[0][0].$truncate_string;
          $length = $matches[0][1] + $strlen($truncate_string);
        }
        else
        {
          $match = end($matches);
          $truncate_string = $match[0].$truncate_string;
          $length = $match[1] + $strlen($truncate_string);
        }
      }
    }

    $truncate_text = $substr($text, 0, $length - $strlen($truncate_string));
    if ($truncate_lastspace)
    {
      $truncate_text = preg_replace('/\s+?(\S+)?$/', '', $truncate_text);
    }
    $text = $truncate_text.$truncate_string;
  }

  if ($mbstring)
  {
   @mb_internal_encoding($old_encoding);
  }

  return $text;
}

/**
 * Highlights the +phrase+ where it is found in the +text+ by surrounding it like
 * <strong class="highlight">I'm a highlight phrase</strong>. The highlighter can be specialized by
 * passing +highlighter+ as single-quoted string with \1 where the phrase is supposed to be inserted.
 * N.B.: The +phrase+ is sanitized to include only letters, digits, and spaces before use.
 *
 * @param string $text subject input to preg_replace.
 * @param mixed $phrase string, array or sfOutputEscaperArrayDecorator instance of words to highlight
 * @param string $highlighter regex replacement input to preg_replace.
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
 * Extracts an excerpt from the +text+ surrounding the +phrase+ with a number of characters on each side determined
 * by +radius+. If the phrase isn't found, an empty string is returned.
 * Example: excerpt("hello my world", "my", 3) => "...lo my wo..."
 * If +excerpt_space+ is true the text will be truncated on only whitespace, not in the middle of words.
 * This might return a smaller radius than specified.
 * Example: excerpt("hello my world", "my", 3, "...", true) => "... my ..."
 *
 * @param string $text The original text
 * @param string $phrase The phrase to excerpt
 * @param string $excerpt_string The string to add before & after excerpted phrase
 * @param bool $excerpt_space If true the text will be truncated on only whitespace, not in the middle of words
 *
 * @return string
 */
function excerpt_text($text, $phrase, $radius = 100, $excerpt_string = '...', $excerpt_space = false)
{
  if ($text == '' || $phrase == '')
  {
    return '';
  }

  $mbstring = extension_loaded('mbstring');
  if($mbstring)
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
    $prefix = ($start_pos > 0) ? $excerpt_string : '';
    $postfix = $end_pos < $strlen($text) ? $excerpt_string : '';

    if ($excerpt_space)
    {
      // only cut off at ends where $exceprt_string is added
      if($prefix)
      {
        $excerpt = preg_replace('/^(\S+)?\s+?/', ' ', $excerpt);
      }
      if($postfix)
      {
        $excerpt = preg_replace('/\s+?(\S+)?$/', ' ', $excerpt);
      }
    }

    $return_string = $prefix.$excerpt.$postfix;
  }

  if($mbstring)
  {
    @mb_internal_encoding($old_encoding);
  }
  return $return_string;
}

/**
 * Word wrap long lines to line_width
 *
 * @param string $text The original text
 * @param integer $line_width The length to wrap the lines
 *
 * @return string
 */
function wrap_text($text, $line_width = 80)
{
  return preg_replace('/(.{1,'.$line_width.'})(\s+|$)/s', "\\1\n", preg_replace("/\n/", "\n\n", $text));
}

/**
 * Returns +text+ transformed into html using very simple formatting rules
 * Surrounds paragraphs with <tt>&lt;p&gt;</tt> tags, and converts line breaks into <tt>&lt;br /&gt;</tt>
 * Two consecutive newlines(<tt>\n\n</tt>) are considered as a paragraph, one newline (<tt>\n</tt>) is
 * considered a linebreak, three or more consecutive newlines are turned into two newlines
 *
 * @param string $text The original text
 * @param array $options Html options for paragraphs
 *
 * @return string
 */
function simple_format_text($text, $options = array())
{
  $css = (isset($options['class'])) ? ' class="'.$options['class'].'"' : '';

  $text = sfToolkit::pregtr($text, array("/(\r\n|\r)/"        => "\n",               // lets make them newlines crossplatform
                                         "/\n{2,}/"           => "</p><p$css>"));    // turn two and more newlines into paragraph

  // turn single newline into <br/>
  $text = str_replace("\n", "\n<br />", $text);
  return '<p'.$css.'>'.$text.'</p>'; // wrap the first and last line in paragraphs before we're done
}

/**
 * Turns all urls and email addresses into clickable links
 *
 * Example:
 *   auto_link("Go to http://www.symfony-project.com and say hello to fabien.potencier@example.com") =>
 *     Go to <a href="http://www.symfony-project.com">http://www.symfony-project.com</a> and
 *     say hello to <a href="mailto:fabien.potencier@example.com">fabien.potencier@example.com</a>
 *
 * @param string $text The original text
 * @param string $link Define what should be linked. Options are 'all' (default), 'email_addresses' and 'urls'
 * @param array $html_options Html options for the links
 * @param bool $truncate Truncate link or not
 * @param integer $truncate_length Truncate length. Used only if $truncate is true
 * @param string $truncate_pad The string to add after truncated text. Used only if $truncate is true
 * @param bool $is_unicode If true, allows the inclusion of unicode characters in email addresses
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
 * Removes links from text
 * Examples: strip_links_text('<a href="http://www.google.com">Google</a>') => Google
 *  strip_links_text('<a href="mailto:me@dot.com">Email Me!</a>') => Email Me!
 *
 * @param string $text The original text
 *
 * @return string
 */
function strip_links_text($text)
{
  return preg_replace('/<a[^>]*>(.*?)<\/a>/s', '\\1', $text);
}

if (!defined('SF_AUTO_LINK_RE'))
{
  define('SF_AUTO_LINK_RE', '~
    (                       # leading text
      <\w+.*?>|             #   leading HTML tag, or
      [^=!:\'"/]|           #   leading punctuation, or
      ^                     #   beginning of line
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
      (?:\#[\w\-/\?!=]*)?        # trailing anchor
    )
    ([[:punct:]]|\s|<|$)    # trailing text
   ~x');
}

/**
 * Turns all URLs into clickable links.
 * Ignores URLs that are already linked.
 *
 * @param string $text The original text
 * @param array $html_options Html options for the links
 * @param bool $truncate Truncate link or not
 * @param integer $truncate_length Truncate length. Used only if $truncate is true
 * @param string $truncate_pad The string to add after truncated text. Used only if $truncate is true
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

/**
 * Turns email addresses into clickable links.
 * Ignores email addresses that are already linked.
 *
 * @param string $text The original text
 * @param array $html_options Html options for the links
 * @param bool $is_unicode If true, allows the inclusion of unicode characters in email addresses
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
