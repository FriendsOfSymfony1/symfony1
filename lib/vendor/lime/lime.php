<?php

/**
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/src/lime_test.php';
require_once __DIR__.'/src/lime_harness.php';

class lime_output
{
  public $colorizer = null;
  public $base_dir = null;

  public function __construct($force_colors = false, $base_dir = null)
  {
    $this->colorizer = new lime_colorizer($force_colors);
    $this->base_dir = $base_dir === null ? getcwd() : $base_dir;
  }

  public function diag()
  {
    $messages = func_get_args();
    foreach ($messages as $message)
    {
      echo $this->colorizer->colorize('# '.join("\n# ", (array) $message), 'COMMENT')."\n";
    }
  }

  public function comment($message)
  {
    echo $this->colorizer->colorize(sprintf('# %s', $message), 'COMMENT')."\n";
  }

  public function info($message)
  {
    echo $this->colorizer->colorize(sprintf('> %s', $message), 'INFO_BAR')."\n";
  }

  public function error($message, $file = null, $line = null, $traces = array())
  {
    if ($file !== null)
    {
      $message .= sprintf("\n(in %s on line %s)", $file, $line);
    }

    // some error messages contain absolute file paths
    $message = $this->strip_base_dir($message);

    $space = $this->colorizer->colorize(str_repeat(' ', 71), 'RED_BAR')."\n";
    $message = trim($message);
    $message = wordwrap($message, 66, "\n");

    echo "\n".$space;
    foreach (explode("\n", $message) as $message_line)
    {
      echo $this->colorizer->colorize(str_pad('  '.$message_line, 71, ' '), 'RED_BAR')."\n";
    }
    echo $space."\n";

    if (count($traces) > 0)
    {
      echo $this->colorizer->colorize('Exception trace:', 'COMMENT')."\n";

      $this->print_trace(null, $file, $line);

      foreach ($traces as $trace)
      {
        if (array_key_exists('class', $trace))
        {
          $method = sprintf('%s%s%s()', $trace['class'], $trace['type'], $trace['function']);
        }
        else
        {
          $method = sprintf('%s()', $trace['function']);
        }

        if (array_key_exists('file', $trace))
        {
          $this->print_trace($method, $trace['file'], $trace['line']);
        }
        else
        {
          $this->print_trace($method);
        }
      }

      echo "\n";
    }
  }

  protected function print_trace($method = null, $file = null, $line = null)
  {
    if (!is_null($method))
    {
      $method .= ' ';
    }

    echo '  '.$method.'at ';

    if (!is_null($file) && !is_null($line))
    {
      printf("%s:%s\n", $this->colorizer->colorize($this->strip_base_dir($file), 'TRACE'), $this->colorizer->colorize($line, 'TRACE'));
    }
    else
    {
      echo "[internal function]\n";
    }
  }

  public function echoln($message, $colorizer_parameter = null, $colorize = true)
  {
    if ($colorize)
    {
      $colorizer = $this->colorizer;
      $message = preg_replace_callback(
        '/(?:^|\.)((?:not ok|dubious|errors) *\d*)\b/',
        function ($match) use ($colorizer) {
          return $colorizer->colorize($match[1], 'ERROR');
        },
        $message
      );
      $message = preg_replace_callback(
        '/(?:^|\.)(ok *\d*)\b/',
        function ($match) use ($colorizer) {
          return $colorizer->colorize($match[1], 'INFO');
        },
        $message
      );
      $message = preg_replace_callback(
        '/"(.+?)"/',
        function ($match) use ($colorizer) {
          return $colorizer->colorize($match[1], 'PARAMETER');
        },
        $message
      );
      $message = preg_replace_callback(
        '/(\->|\:\:)?([a-zA-Z0-9_]+?)\(\)/',
        function ($match) use ($colorizer) {
          return $colorizer->colorize($match[1].$match[2].'()', 'PARAMETER');
        },
        $message
      );
    }

    echo ($colorizer_parameter ? $this->colorizer->colorize($message, $colorizer_parameter) : $message)."\n";
  }

  public function green_bar($message)
  {
    echo $this->colorizer->colorize($message.str_repeat(' ', 71 - min(71, strlen($message))), 'GREEN_BAR')."\n";
  }

  public function red_bar($message)
  {
    echo $this->colorizer->colorize($message.str_repeat(' ', 71 - min(71, strlen($message))), 'RED_BAR')."\n";
  }

  protected function strip_base_dir($text)
  {
    return str_replace(DIRECTORY_SEPARATOR, '/', str_replace(realpath($this->base_dir).DIRECTORY_SEPARATOR, '', $text));
  }
}

class lime_output_color extends lime_output
{
}

class lime_colorizer
{
  static public $styles = array();

  protected $colors_supported = false;

  public function __construct($force_colors = false)
  {
    if ($force_colors)
    {
      $this->colors_supported = true;
    }
    else
    {
      // colors are supported on windows with ansicon or on tty consoles
      if (DIRECTORY_SEPARATOR == '\\')
      {
        $this->colors_supported = false !== getenv('ANSICON');
      }
      else
      {
        $this->colors_supported = function_exists('posix_isatty') && @posix_isatty(STDOUT);
      }
    }
  }

  public static function style($name, $options = array())
  {
    self::$styles[$name] = $options;
  }

  public function colorize($text = '', $parameters = array())
  {

    if (!$this->colors_supported)
    {
      return $text;
    }

    static $options    = array('bold' => 1, 'underscore' => 4, 'blink' => 5, 'reverse' => 7, 'conceal' => 8);
    static $foreground = array('black' => 30, 'red' => 31, 'green' => 32, 'yellow' => 33, 'blue' => 34, 'magenta' => 35, 'cyan' => 36, 'white' => 37);
    static $background = array('black' => 40, 'red' => 41, 'green' => 42, 'yellow' => 43, 'blue' => 44, 'magenta' => 45, 'cyan' => 46, 'white' => 47);

    !is_array($parameters) && isset(self::$styles[$parameters]) and $parameters = self::$styles[$parameters];

    $codes = array();
    isset($parameters['fg']) and $codes[] = $foreground[$parameters['fg']];
    isset($parameters['bg']) and $codes[] = $background[$parameters['bg']];
    foreach ($options as $option => $value)
    {
      isset($parameters[$option]) && $parameters[$option] and $codes[] = $value;
    }

    return "\033[".implode(';', $codes).'m'.$text."\033[0m";
  }
}

lime_colorizer::style('ERROR', array('bg' => 'red', 'fg' => 'white', 'bold' => true));
lime_colorizer::style('INFO', array('fg' => 'green', 'bold' => true));
lime_colorizer::style('TRACE', array('fg' => 'green', 'bold' => true));
lime_colorizer::style('PARAMETER', array('fg' => 'cyan'));
lime_colorizer::style('COMMENT', array('fg' => 'yellow'));

lime_colorizer::style('GREEN_BAR', array('fg' => 'white', 'bg' => 'green', 'bold' => true));
lime_colorizer::style('RED_BAR', array('fg' => 'white', 'bg' => 'red', 'bold' => true));
lime_colorizer::style('INFO_BAR', array('fg' => 'cyan', 'bold' => true));

class lime_coverage extends lime_registration
{
  public $files = array();
  public $extension = '.php';
  public $base_dir = '';
  public $harness = null;
  public $verbose = false;
  protected $coverage = array();

  public function __construct($harness)
  {
    $this->harness = $harness;

    if (!function_exists('xdebug_start_code_coverage'))
    {
      throw new Exception('You must install and enable xdebug before using lime coverage.');
    }

    if (!ini_get('xdebug.extended_info'))
    {
      throw new Exception('You must set xdebug.extended_info to 1 in your php.ini to use lime coverage.');
    }
  }

  public function run()
  {
    if (!count($this->harness->files))
    {
      throw new Exception('You must register some test files before running coverage!');
    }

    if (!count($this->files))
    {
      throw new Exception('You must register some files to cover!');
    }

    $this->coverage = array();

    $this->process($this->harness->files);

    $this->output($this->files);
  }

  public function process($files)
  {
    if (!is_array($files))
    {
      $files = array($files);
    }

    $tmp_file = sys_get_temp_dir().DIRECTORY_SEPARATOR.'test.php';
    foreach ($files as $file)
    {
      $tmp = <<<EOF
<?php
xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
include('$file');
echo '<PHP_SER>'.serialize(xdebug_get_code_coverage()).'</PHP_SER>';
EOF;
      file_put_contents($tmp_file, $tmp);
      ob_start();
      // see http://trac.symfony-project.org/ticket/5437 for the explanation on the weird "cd" thing
      passthru(sprintf('cd & %s %s 2>&1', escapeshellarg($this->harness->php_cli), escapeshellarg($tmp_file)), $return);
      $retval = ob_get_clean();

      if (0 != $return) // test exited without success
      {
        // something may have gone wrong, we should warn the user so they know
        // it's a bug in their code and not symfony's

        $this->harness->output->echoln(sprintf('Warning: %s returned status %d, results may be inaccurate', $file, $return), 'ERROR');
      }

      if (false === $cov = @unserialize(substr($retval, strpos($retval, '<PHP_SER>') + 9, strpos($retval, '</PHP_SER>') - 9)))
      {
        if (0 == $return)
        {
          // failed to serialize, but PHP said it should of worked.
          // something is seriously wrong, so abort with exception
          throw new Exception(sprintf('Unable to unserialize coverage for file "%s"', $file));
        }
        else
        {
          // failed to serialize, but PHP warned us that this might have happened.
          // so we should ignore and move on
          continue; // continue foreach loop through $this->harness->files
        }
      }

      foreach ($cov as $file => $lines)
      {
        if (!isset($this->coverage[$file]))
        {
          $this->coverage[$file] = $lines;
          continue;
        }

        foreach ($lines as $line => $flag)
        {
          if ($flag == 1)
          {
            $this->coverage[$file][$line] = 1;
          }
        }
      }
    }

    if (file_exists($tmp_file))
    {
      unlink($tmp_file);
    }
  }

  public function output($files)
  {
    ksort($this->coverage);
    $total_php_lines = 0;
    $total_covered_lines = 0;
    foreach ($files as $file)
    {
      $file = realpath($file);
      $is_covered = isset($this->coverage[$file]);
      $cov = isset($this->coverage[$file]) ? $this->coverage[$file] : array();
      $covered_lines = array();
      $missing_lines = array();

      foreach ($cov as $line => $flag)
      {
        switch ($flag)
        {
          case 1:
            $covered_lines[] = $line;
            break;
          case -1:
            $missing_lines[] = $line;
            break;
        }
      }

      $total_lines = count($covered_lines) + count($missing_lines);
      if (!$total_lines)
      {
        // probably means that the file is not covered at all!
        $total_lines = count($this->get_php_lines(file_get_contents($file)));
      }

      $output = $this->harness->output;
      $percent = $total_lines ? count($covered_lines) * 100 / $total_lines : 0;

      $total_php_lines += $total_lines;
      $total_covered_lines += count($covered_lines);

      $relative_file = $this->get_relative_file($file);
      $output->echoln(sprintf("%-70s %3.0f%%", substr($relative_file, -min(70, strlen($relative_file))), $percent), $percent == 100 ? 'INFO' : ($percent > 90 ? 'PARAMETER' : ($percent < 20 ? 'ERROR' : '')));
      if ($this->verbose && $is_covered && $percent != 100)
      {
        $output->comment(sprintf("missing: %s", $this->format_range($missing_lines)));
      }
    }

    $output->echoln(sprintf("TOTAL COVERAGE: %3.0f%%", $total_php_lines ? $total_covered_lines * 100 / $total_php_lines : 0));
  }

  public static function get_php_lines($content)
  {
    if (is_readable($content))
    {
      $content = file_get_contents($content);
    }

    $tokens = token_get_all($content);
    $php_lines = array();
    $current_line = 1;
    $in_class = false;
    $in_function = false;
    $in_function_declaration = false;
    $end_of_current_expr = true;
    $open_braces = 0;
    foreach ($tokens as $token)
    {
      if (is_string($token))
      {
        switch ($token)
        {
          case '=':
            if (false === $in_class || (false !== $in_function && !$in_function_declaration))
            {
              $php_lines[$current_line] = true;
            }
            break;
          case '{':
            ++$open_braces;
            $in_function_declaration = false;
            break;
          case ';':
            $in_function_declaration = false;
            $end_of_current_expr = true;
            break;
          case '}':
            $end_of_current_expr = true;
            --$open_braces;
            if ($open_braces == $in_class)
            {
              $in_class = false;
            }
            if ($open_braces == $in_function)
            {
              $in_function = false;
            }
            break;
        }

        continue;
      }

      list($id, $text) = $token;

      switch ($id)
      {
        case T_CURLY_OPEN:
        case T_DOLLAR_OPEN_CURLY_BRACES:
          ++$open_braces;
          break;
        case T_WHITESPACE:
        case T_OPEN_TAG:
        case T_CLOSE_TAG:
          $end_of_current_expr = true;
          $current_line += count(explode("\n", $text)) - 1;
          break;
        case T_COMMENT:
        case T_DOC_COMMENT:
          $current_line += count(explode("\n", $text)) - 1;
          break;
        case T_CLASS:
          $in_class = $open_braces;
          break;
        case T_FUNCTION:
          $in_function = $open_braces;
          $in_function_declaration = true;
          break;
        case T_AND_EQUAL:
        case T_BREAK:
        case T_CASE:
        case T_CATCH:
        case T_CLONE:
        case T_CONCAT_EQUAL:
        case T_CONTINUE:
        case T_DEC:
        case T_DECLARE:
        case T_DEFAULT:
        case T_DIV_EQUAL:
        case T_DO:
        case T_ECHO:
        case T_ELSEIF:
        case T_EMPTY:
        case T_ENDDECLARE:
        case T_ENDFOR:
        case T_ENDFOREACH:
        case T_ENDIF:
        case T_ENDSWITCH:
        case T_ENDWHILE:
        case T_EVAL:
        case T_EXIT:
        case T_FOR:
        case T_FOREACH:
        case T_GLOBAL:
        case T_IF:
        case T_INC:
        case T_INCLUDE:
        case T_INCLUDE_ONCE:
        case T_INSTANCEOF:
        case T_ISSET:
        case T_IS_EQUAL:
        case T_IS_GREATER_OR_EQUAL:
        case T_IS_IDENTICAL:
        case T_IS_NOT_EQUAL:
        case T_IS_NOT_IDENTICAL:
        case T_IS_SMALLER_OR_EQUAL:
        case T_LIST:
        case T_LOGICAL_AND:
        case T_LOGICAL_OR:
        case T_LOGICAL_XOR:
        case T_MINUS_EQUAL:
        case T_MOD_EQUAL:
        case T_MUL_EQUAL:
        case T_NEW:
        case T_OBJECT_OPERATOR:
        case T_OR_EQUAL:
        case T_PLUS_EQUAL:
        case T_PRINT:
        case T_REQUIRE:
        case T_REQUIRE_ONCE:
        case T_RETURN:
        case T_SL:
        case T_SL_EQUAL:
        case T_SR:
        case T_SR_EQUAL:
        case T_SWITCH:
        case T_THROW:
        case T_TRY:
        case T_UNSET:
        case T_UNSET_CAST:
        case T_USE:
        case T_WHILE:
        case T_XOR_EQUAL:
          $php_lines[$current_line] = true;
          $end_of_current_expr = false;
          break;
        default:
          if (false === $end_of_current_expr)
          {
            $php_lines[$current_line] = true;
          }
      }
    }

    return $php_lines;
  }

  public function compute($content, $cov)
  {
    $php_lines = self::get_php_lines($content);

    // we remove from $cov non php lines
    foreach (array_diff_key($cov, $php_lines) as $line => $tmp)
    {
      unset($cov[$line]);
    }

    return array($cov, $php_lines);
  }

  public function format_range($lines)
  {
    sort($lines);
    $formatted = '';
    $first = -1;
    $last = -1;
    foreach ($lines as $line)
    {
      if ($last + 1 != $line)
      {
        if ($first != -1)
        {
          $formatted .= $first == $last ? "$first " : "[$first - $last] ";
        }
        $first = $line;
        $last = $line;
      }
      else
      {
        $last = $line;
      }
    }
    if ($first != -1)
    {
      $formatted .= $first == $last ? "$first " : "[$first - $last] ";
    }

    return $formatted;
  }
}

class lime_registration
{
  public $files = array();
  public $extension = '.php';
  public $base_dir = '';

  public function register($files_or_directories)
  {
    foreach ((array) $files_or_directories as $f_or_d)
    {
      if (is_file($f_or_d))
      {
        $this->files[] = realpath($f_or_d);
      }
      elseif (is_dir($f_or_d))
      {
        $this->register_dir($f_or_d);
      }
      else
      {
        throw new Exception(sprintf('The file or directory "%s" does not exist.', $f_or_d));
      }
    }
  }

  public function register_glob($glob)
  {
    if ($dirs = glob($glob))
    {
      foreach ($dirs as $file)
      {
        $this->files[] = realpath($file);
      }
    }
  }

  public function register_dir($directory)
  {
    if (!is_dir($directory))
    {
      throw new Exception(sprintf('The directory "%s" does not exist.', $directory));
    }

    $files = array();

    $current_dir = opendir($directory);
    while ($entry = readdir($current_dir))
    {
      if ($entry == '.' || $entry == '..') continue;

      if (is_dir($entry))
      {
        $this->register_dir($entry);
      }
      elseif (preg_match('#'.$this->extension.'$#', $entry))
      {
        $files[] = realpath($directory.DIRECTORY_SEPARATOR.$entry);
      }
    }

    $this->files = array_merge($this->files, $files);
  }

  protected function get_relative_file($file)
  {
    return str_replace(DIRECTORY_SEPARATOR, '/', str_replace(array(realpath($this->base_dir).DIRECTORY_SEPARATOR, $this->extension), '', $file));
  }
}
