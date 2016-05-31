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
 * @param string $truncate_lastspace Remove or not last space after truncate
 * @param string $truncate_pattern Pattern
 * @param integer $length_max Used only with truncate_patter
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
  $strlen = $mbstring ? 'mb_strlen' : 'strlen';
  $substr = $mbstring ? 'mb_substr' : 'substr';

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
 * by +radius+. If the phrase isn't found, nil is returned. Ex:
 *   excerpt("hello my world", "my", 3) => "...lo my wo..."
 * If +excerpt_space+ is true the text will only be truncated on whitespace, never inbetween words.
 * This might return a smaller radius than specified.
 *   excerpt("hello my world", "my", 3, "...", true) => "... my ..."
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
 * Word wrap long lines to line_width.
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
 * Turns all urls and email addresses into clickable links. The +link+ parameter can limit what should be linked.
 * Options are :all (default), :email_addresses, and :urls.
 *
 * Example:
 *   auto_link("Go to http://www.symfony-project.com and say hello to fabien.potencier@example.com") =>
 *     Go to <a href="http://www.symfony-project.com">http://www.symfony-project.com</a> and
 *     say hello to <a href="mailto:fabien.potencier@example.com">fabien.potencier@example.com</a>
 */
function auto_link_text($text, $link = 'all', $href_options = array(), $truncate = false, $truncate_len = 35, $pad = '...')
{
  if ($link == 'all')
  {
    return _auto_link_urls(_auto_link_email_addresses($text), $href_options, $truncate, $truncate_len, $pad);
  }
  else if ($link == 'email_addresses')
  {
    return _auto_link_email_addresses($text);
  }
  else if ($link == 'urls')
  {
    return _auto_link_urls($text, $href_options, $truncate, $truncate_len, $pad);
  }
}

/**
 * Turns all links into words, like "<a href="something">else</a>" to "else".
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
 * Turns all urls into clickable links.
 */
function _auto_link_urls($text, $href_options = array(), $truncate = false, $truncate_len = 40, $pad = '...')
{
  $href_options = _tag_options($href_options);

  $callback_function = '
    if (preg_match("/<a\s/i", $matches[1]))
    {
      return $matches[0];
    }
    ';

  if ($truncate)
  {
    $callback_function .= '
      else if (strlen($matches[2].$matches[3]) > '.$truncate_len.')
      {
        return $matches[1].\'<a href="\'.($matches[2] == "www." ? "http://www." : $matches[2]).$matches[3].\'"'.$href_options.'>\'.substr($matches[2].$matches[3], 0, '.$truncate_len.').\''.$pad.'</a>\'.$matches[4];
      }
      ';
  }

  $callback_function .= '
    else
    {
      return $matches[1].\'<a href="\'.($matches[2] == "www." ? "http://www." : $matches[2]).$matches[3].\'"'.$href_options.'>\'.$matches[2].$matches[3].\'</a>\'.$matches[4];
    }
    ';

  return preg_replace_callback(
    SF_AUTO_LINK_RE,
    create_function('$matches', $callback_function),
    $text
    );
}

/**
 * Turns all email addresses into clickable links.
 */
function _auto_link_email_addresses($text)
{
  // Taken from http://snippets.dzone.com/posts/show/6156
  return preg_replace("#(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $text);

  // Removed since it destroys already linked emails
  // Example:   <a href="mailto:me@example.com">bar</a> gets <a href="mailto:me@example.com">bar</a> gets <a href="mailto:<a href="mailto:me@example.com">bar</a>
  //return preg_replace('/([\w\.!#\$%\-+.]+@[A-Za-z0-9\-]+(\.[A-Za-z0-9\-]+)+)/', '<a href="mailto:\\1">\\1</a>', $text);
}
