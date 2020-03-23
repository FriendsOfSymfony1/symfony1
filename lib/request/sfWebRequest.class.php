<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2004-2006 Sean Kerr <sean@code-box.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWebRequest class.
 *
 * This class manages web requests. It parses input from the request and store them as parameters.
 *
 * @package    symfony
 * @subpackage request
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 * @version    SVN: $Id$
 */
class sfWebRequest extends sfRequest
{
  const
    PORT_HTTP  = 80,
    PORT_HTTPS = 443;

  protected
    $languages              = null,
    $charsets               = null,
    $acceptableContentTypes = null,
    $pathInfoArray          = null,
    $relativeUrlRoot        = null,
    $getParameters          = null,
    $postParameters         = null,
    $requestParameters      = null,
    $formats                = array(),
    $format                 = null,
    $fixedFileArray         = false;

  /**
   * Initializes this sfRequest.
   *
   * Available options:
   *
   *  * formats:           The list of supported format and their associated mime-types
   *  * path_info_key:     The path info key (default to PATH_INFO)
   *  * path_info_array:   The path info array (default to SERVER)
   *  * relative_url_root: The relative URL root
   *  * http_port:         The port to use for HTTP requests
   *  * https_port:        The port to use for HTTPS requests
   *
   * @param  sfEventDispatcher $dispatcher  An sfEventDispatcher instance
   * @param  array             $parameters  An associative array of initialization parameters
   * @param  array             $attributes  An associative array of initialization attributes
   * @param  array             $options     An associative array of options
   *
   * @return void
   *
   * @throws <b>sfInitializationException</b> If an error occurs while initializing this sfRequest
   *
   * @see sfRequest
   */
  public function initialize(sfEventDispatcher $dispatcher, $parameters = array(), $attributes = array(), $options = array())
  {
    $options = array_merge(array(
      'path_info_key'   => 'PATH_INFO',
      'path_info_array' => 'SERVER',
      'http_port'       => null,
      'https_port'      => null,
      'default_format'  => null, // to maintain bc
      'trust_proxy'     => true, // to maintain bc
    ), $options);
    parent::initialize($dispatcher, $parameters, $attributes, $options);

    // GET parameters
    if (version_compare(PHP_VERSION, '5.4.0-dev', '<') && get_magic_quotes_gpc())
    {
      $this->getParameters = sfToolkit::stripslashesDeep($_GET);
    }
    else
    {
      $this->getParameters = $_GET;
    }
    $this->parameterHolder->add($this->getParameters);

    $postParameters = $_POST;

    if (isset($_SERVER['REQUEST_METHOD']))
    {
      switch ($_SERVER['REQUEST_METHOD'])
      {
        case 'GET':
          $this->setMethod(self::GET);
          break;

        case 'POST':
          if (isset($postParameters['sf_method']))
          {
            $this->setMethod(strtoupper($postParameters['sf_method']));
            unset($postParameters['sf_method']);
          }
          elseif (isset($this->getParameters['sf_method']))
          {
            $this->setMethod(strtoupper($this->getParameters['sf_method']));
            unset($this->getParameters['sf_method']);
          }
          else
          {
            $this->setMethod(self::POST);
          }
          $this->parameterHolder->remove('sf_method');
          break;

        case 'PUT':
          $this->setMethod(self::PUT);
          if ('application/x-www-form-urlencoded' === $this->getContentType())
          {
            parse_str($this->getContent(), $postParameters);
          }
          break;

        case 'PATCH':
          $this->setMethod(self::PATCH);
          if ('application/x-www-form-urlencoded' === $this->getContentType())
          {
            parse_str($this->getContent(), $postParameters);
          }
          break;

        case 'DELETE':
          $this->setMethod(self::DELETE);
          if ('application/x-www-form-urlencoded' === $this->getContentType())
          {
            parse_str($this->getContent(), $postParameters);
          }
          break;

        case 'HEAD':
          $this->setMethod(self::HEAD);
          break;

        case 'OPTIONS':
          $this->setMethod(self::OPTIONS);
          break;

        default:
          $this->setMethod(self::GET);
      }
    }
    else
    {
      // set the default method
      $this->setMethod(self::GET);
    }

    if (version_compare(PHP_VERSION, '5.4.0-dev', '<') && get_magic_quotes_gpc())
    {
      $this->postParameters = sfToolkit::stripslashesDeep($postParameters);
    }
    else
    {
      $this->postParameters = $postParameters;
    }

    $this->parameterHolder->add($this->postParameters);

    if ($formats = $this->getOption('formats'))
    {
      foreach ($formats as $format => $mimeTypes)
      {
        $this->setFormat($format, $mimeTypes);
      }
    }

    // additional parameters
    $this->requestParameters = $this->parseRequestParameters();
    $this->parameterHolder->add($this->requestParameters);

    $this->fixParameters();
  }

  /**
   * Returns the content type of the current request.
   *
   * @param  Boolean $trim If false the full Content-Type header will be returned
   *
   * @return string
   */
  public function getContentType($trim = true)
  {
    $contentType = $this->getHttpHeader('Content-Type', null);

    if ($trim && false !== $pos = strpos($contentType, ';'))
    {
      $contentType = substr($contentType, 0, $pos);
    }

    return $contentType;
  }

  /**
   * Retrieves the uniform resource identifier for the current web request.
   *
   * @return string Unified resource identifier
   */
  public function getUri()
  {
    $pathArray = $this->getPathInfoArray();

    // for IIS with rewrite module (IIFR, ISAPI Rewrite, ...)
    if ('HTTP_X_REWRITE_URL' == $this->getOption('path_info_key'))
    {
      $uri = isset($pathArray['HTTP_X_REWRITE_URL']) ? $pathArray['HTTP_X_REWRITE_URL'] : '';
    }
    else
    {
      $uri = isset($pathArray['REQUEST_URI']) ? $pathArray['REQUEST_URI'] : '';
    }

    return $this->isAbsUri() ? $uri : $this->getUriPrefix().$uri;
  }

  /**
   * See if the client is using absolute uri
   *
   * @return boolean true, if is absolute uri otherwise false
   */
  public function isAbsUri()
  {
    $pathArray = $this->getPathInfoArray();

    return isset($pathArray['REQUEST_URI']) ? 0 === strpos($pathArray['REQUEST_URI'], 'http') : false;
  }

  /**
   * Returns Uri prefix, including protocol, hostname and server port.
   *
   * @return string Uniform resource identifier prefix
   */
  public function getUriPrefix()
  {
    $pathArray = $this->getPathInfoArray();
    $secure = $this->isSecure();

    $protocol = $secure ? 'https' : 'http';
    $host = $this->getHost();
    $port = null;

    // extract port from host or environment variable
    if (false !== strpos($host, ':'))
    {
      list($host, $port) = explode(':', $host, 2);
    }
    else if ($protocolPort = $this->getOption($protocol.'_port'))
    {
      $port = $protocolPort;
    }
    else if (isset($pathArray['SERVER_PORT']))
    {
      $port = $pathArray['SERVER_PORT'];
    }

    // cleanup the port based on whether the current request is forwarded from
    // a secure one and whether the introspected port matches the standard one
    if ($this->isForwardedSecure())
    {
      $port = self::PORT_HTTPS != $this->getOption('https_port') ? $this->getOption('https_port') : null;
    }
    elseif (($secure && self::PORT_HTTPS == $port) || (!$secure && self::PORT_HTTP == $port))
    {
      $port = null;
    }

    return sprintf('%s://%s%s', $protocol, $host, $port ? ':'.$port : '');
  }

  /**
   * Retrieves the path info for the current web request.
   *
   * @return string Path info
   */
  public function getPathInfo()
  {
    $pathInfo = '';

    $pathArray = $this->getPathInfoArray();

    // simulate PATH_INFO if needed
    $sf_path_info_key = $this->getOption('path_info_key');
    if (!isset($pathArray[$sf_path_info_key]) || !$pathArray[$sf_path_info_key])
    {
      if (isset($pathArray['REQUEST_URI']))
      {
        $qs = isset($pathArray['QUERY_STRING']) ? $pathArray['QUERY_STRING'] : '';
        $script_name = $this->getScriptName();
        $uri_prefix = $this->isAbsUri() ? $this->getUriPrefix() : '';
        $pathInfo = preg_replace('/^'.preg_quote($uri_prefix, '/').'/','',$pathArray['REQUEST_URI']);
        $pathInfo = preg_replace('/^'.preg_quote($script_name, '/').'/', '', $pathInfo);
        $prefix_name = preg_replace('#/[^/]+$#', '', $script_name);
        $pathInfo = preg_replace('/^'.preg_quote($prefix_name, '/').'/', '', $pathInfo);
        $pathInfo = preg_replace('/\??'.preg_quote($qs, '/').'$/', '', $pathInfo);
      }
    }
    else
    {
      $pathInfo = $pathArray[$sf_path_info_key];
      if ($relativeUrlRoot = $this->getRelativeUrlRoot())
      {
        $pathInfo = preg_replace('/^'.str_replace('/', '\\/', $relativeUrlRoot).'\//', '', $pathInfo);
      }
    }

    // for IIS
    if (isset($_SERVER['SERVER_SOFTWARE']) && false !== stripos($_SERVER['SERVER_SOFTWARE'], 'iis') && $pos = stripos($pathInfo, '.php'))
    {
      $pathInfo = substr($pathInfo, $pos + 4);
    }

    if (!$pathInfo)
    {
      $pathInfo = '/';
    }

    return $pathInfo;
  }

  /**
   * Returns the relative url root if defined computed with script name if defined
   *
   * @return string The path info prefix
   */
  public function getPathInfoPrefix()
  {
    $prefix = $this->getRelativeUrlRoot();

    if (!$this->getOption('no_script_name'))
    {
      $scriptName = $this->getScriptName();
      $prefix = null === $prefix ? $scriptName : $prefix.'/'.basename($scriptName);
    }

    return $prefix;
  }

  /**
   * Gets GET parameters from request
   *
   * @return array
   */
  public function getGetParameters()
  {
    return $this->getParameters;
  }

  /**
   * Gets POST parameters from request
   *
   * @return array
   */
  public function getPostParameters()
  {
    return $this->postParameters;
  }

  /**
   * Gets REQUEST parameters from request
   *
   * @return array
   */
  public function getRequestParameters()
  {
    return $this->requestParameters;
  }

  /**
   * Add fixed REQUEST parameters
   *
   * @param array $parameters
   */
  public function addRequestParameters(array $parameters)
  {
    $this->requestParameters = array_merge($this->requestParameters, $parameters);
    $this->getParameterHolder()->add($parameters);

    $this->fixParameters();
  }

  /**
   * Returns referer.
   *
   * @return string
   */
  public function getReferer()
  {
    $pathArray = $this->getPathInfoArray();

    return isset($pathArray['HTTP_REFERER']) ? $pathArray['HTTP_REFERER'] : '';
  }

  /**
   * Returns current host name.
   *
   * @return string
   */
  public function getHost()
  {
    $pathArray = $this->getPathInfoArray();

    if ($this->getOption('trust_proxy') && isset($pathArray['HTTP_X_FORWARDED_HOST']))
    {
      $elements = explode(',', $pathArray['HTTP_X_FORWARDED_HOST']);

      return trim($elements[count($elements) - 1]);
    }

    return isset($pathArray['HTTP_HOST']) ? $pathArray['HTTP_HOST'] : '';
  }

  /**
   * Returns current script name.
   *
   * @return string
   */
  public function getScriptName()
  {
    $pathArray = $this->getPathInfoArray();

    return isset($pathArray['SCRIPT_NAME']) ? $pathArray['SCRIPT_NAME'] : (isset($pathArray['ORIG_SCRIPT_NAME']) ? $pathArray['ORIG_SCRIPT_NAME'] : '');
  }

  /**
   * Checks if the request method is the given one.
   *
   * @param  string $method  The method name
   *
   * @return bool true if the current method is the given one, false otherwise
   */
  public function isMethod($method)
  {
    return strtoupper($method) == $this->getMethod();
  }

  /**
   * Returns the preferred culture for the current request.
   *
   * @param  array  $cultures  An array of ordered cultures available
   *
   * @return string The preferred culture
   */
  public function getPreferredCulture(array $cultures = null)
  {
    $preferredCultures = $this->getLanguages();

    if (null === $cultures)
    {
      return isset($preferredCultures[0]) ? $preferredCultures[0] : null;
    }

    if (!$preferredCultures)
    {
      return $cultures[0];
    }

    $preferredCultures = array_values(array_intersect($preferredCultures, $cultures));

    return isset($preferredCultures[0]) ? $preferredCultures[0] : $cultures[0];
  }

  /**
   * Gets a list of languages acceptable by the client browser
   *
   * @return array Languages ordered in the user browser preferences
   */
  public function getLanguages()
  {
    if ($this->languages)
    {
      return $this->languages;
    }

    if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    {
      return array();
    }

    $languages = $this->splitHttpAcceptHeader($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    foreach ($languages as $lang)
    {
      if (false !== strpos($lang, '-'))
      {
        $codes = explode('-', $lang);
        if ($codes[0] == 'i')
        {
          // Language not listed in ISO 639 that are not variants
          // of any listed language, which can be registerd with the
          // i-prefix, such as i-cherokee
          if (count($codes) > 1)
          {
            $lang = $codes[1];
          }
        }
        else
        {
          for ($i = 0, $max = count($codes); $i < $max; $i++)
          {
            if ($i == 0)
            {
              $lang = strtolower($codes[0]);
            }
            else
            {
              $lang .= '_'.strtoupper($codes[$i]);
            }
          }
        }
      }

      $this->languages[] = $lang;
    }

    return $this->languages;
  }

  /**
   * Gets a list of charsets acceptable by the client browser.
   *
   * @return array List of charsets in preferable order
   */
  public function getCharsets()
  {
    if ($this->charsets)
    {
      return $this->charsets;
    }

    if (!isset($_SERVER['HTTP_ACCEPT_CHARSET']))
    {
      return array();
    }

    $this->charsets = $this->splitHttpAcceptHeader($_SERVER['HTTP_ACCEPT_CHARSET']);

    return $this->charsets;
  }

  /**
   * Gets a list of content types acceptable by the client browser
   *
   * @return array Languages ordered in the user browser preferences
   */
  public function getAcceptableContentTypes()
  {
    if ($this->acceptableContentTypes)
    {
      return $this->acceptableContentTypes;
    }

    if (!isset($_SERVER['HTTP_ACCEPT']))
    {
      return array();
    }

    $this->acceptableContentTypes = $this->splitHttpAcceptHeader($_SERVER['HTTP_ACCEPT']);

    return $this->acceptableContentTypes;
  }

  /**
   * Returns true if the request is a XMLHttpRequest.
   *
   * It works if your JavaScript library set an X-Requested-With HTTP header.
   * Works with Prototype, Mootools, jQuery, and perhaps others.
   *
   * @return bool true if the request is an XMLHttpRequest, false otherwise
   */
  public function isXmlHttpRequest()
  {
    return ($this->getHttpHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
  }

  /**
   * Gets the value of HTTP header
   *
   * @param string $name The HTTP header name
   * @param string $prefix The HTTP header prefix
   * @return string The value of HTTP header
   */
  public function getHttpHeader($name, $prefix = 'http')
  {
    if ($prefix)
    {
      $prefix = strtoupper($prefix).'_';
    }

    $name = $prefix.strtoupper(str_replace('-', '_', $name));

    $pathArray = $this->getPathInfoArray();

    return isset($pathArray[$name]) ? sfToolkit::stripslashesDeep($pathArray[$name]) : null;
  }

  /**
   * Gets the value of a cookie.
   *
   * @param  string $name          Cookie name
   * @param  string $defaultValue  Default value returned when no cookie with given name is found
   *
   * @return string The cookie value
   */
  public function getCookie($name, $defaultValue = null)
  {
    $retval = $defaultValue;

    if (isset($_COOKIE[$name]))
    {
      if (version_compare(PHP_VERSION, '5.4.0-dev', '<') && get_magic_quotes_gpc())
      {
        $retval = sfToolkit::stripslashesDeep($_COOKIE[$name]);
      }
      else
      {
        $retval = $_COOKIE[$name];
      }
    }

    return $retval;
  }

  /**
   * Returns true if the current or forwarded request is secure (HTTPS protocol).
   *
   * @return boolean
   */
  public function isSecure()
  {
    $pathArray = $this->getPathInfoArray();

    return
      (isset($pathArray['HTTPS']) && (('on' == strtolower($pathArray['HTTPS']) || 1 == $pathArray['HTTPS'])))
      ||
      ($this->getOption('trust_proxy') && isset($pathArray['HTTP_SSL_HTTPS']) && (('on' == strtolower($pathArray['HTTP_SSL_HTTPS']) || 1 == $pathArray['HTTP_SSL_HTTPS'])))
      ||
      ($this->getOption('trust_proxy') && $this->isForwardedSecure())
    ;
  }

  /**
   * Returns true if the current request is forwarded from a request that is secure.
   *
   * @return boolean
   */
  protected function isForwardedSecure()
  {
    $pathArray = $this->getPathInfoArray();

    return isset($pathArray['HTTP_X_FORWARDED_PROTO']) && 'https' == strtolower($pathArray['HTTP_X_FORWARDED_PROTO']);
  }

  /**
   * Retrieves relative root url.
   *
   * @return string URL
   */
  public function getRelativeUrlRoot()
  {
    if (null === $this->relativeUrlRoot)
    {
      if (!($this->relativeUrlRoot = $this->getOption('relative_url_root')))
      {
        $this->relativeUrlRoot = preg_replace('#/[^/]+\.php5?$#', '', $this->getScriptName());
      }
    }

    return $this->relativeUrlRoot;
  }

  /**
   * Sets the relative root url for the current web request.
   *
   * @param string $value  Value for the url
   */
  public function setRelativeUrlRoot($value)
  {
    $this->relativeUrlRoot = $value;
  }

  /**
   * Splits an HTTP header for the current web request.
   *
   * @param string $header Header to split
   *
   * @return array
   */
  public function splitHttpAcceptHeader($header)
  {
    $values = array();
    $groups = array();
    foreach (array_filter(explode(',', $header)) as $value)
    {
      // Cut off any q-value that might come after a semi-colon
      if ($pos = strpos($value, ';'))
      {
        $q     = trim(substr($value, strpos($value, '=') + 1));
        $value = substr($value, 0, $pos);
      }
      else
      {
        $q = 1;
      }

      $groups[$q][] = $value;
    }

    krsort($groups);

    foreach ($groups as $q => $items) {
      if (0 < $q) {
        foreach ($items as $value) {
          $values[] = trim($value);
        }
      }
    }

    return $values;
  }

  /**
   * Returns the array that contains all request information ($_SERVER or $_ENV).
   *
   * This information is stored in the path_info_array option.
   *
   * @return  array Path information
   */
  public function getPathInfoArray()
  {
    if (!$this->pathInfoArray)
    {
      // parse PATH_INFO
      switch ($this->getOption('path_info_array'))
      {
        case 'SERVER':
          $this->pathInfoArray =& $_SERVER;
          break;

        case 'ENV':
        default:
          $this->pathInfoArray =& $_ENV;
      }
    }

    return $this->pathInfoArray;
  }

  /**
   * Gets the mime type associated with the format.
   *
   * @param  string $format  The format
   *
   * @return string The associated mime type (null if not found)
   */
  public function getMimeType($format)
  {
    return isset($this->formats[$format]) ? $this->formats[$format][0] : null;
  }

  /**
   * Gets the format associated with the mime type.
   *
   * @param  string $mimeType  The associated mime type
   *
   * @return string The format (null if not found)
   */
  public function getFormat($mimeType)
  {
    foreach ($this->formats as $format => $mimeTypes)
    {
      if (in_array($mimeType, $mimeTypes))
      {
        return $format;
      }
    }

    return null;
  }

  /**
   * Associates a format with mime types.
   *
   * @param string       $format     The format
   * @param string|array $mimeTypes  The associated mime types (the preferred one must be the first as it will be used as the content type)
   */
  public function setFormat($format, $mimeTypes)
  {
    $this->formats[$format] = is_array($mimeTypes) ? $mimeTypes : array($mimeTypes);
  }

  /**
   * Sets the request format.
   *
   * @param string $format  The request format
   */
  public function setRequestFormat($format)
  {
    $this->format = $format;
  }

  /**
   * Gets the request format.
   *
   * Here is the process to determine the format:
   *
   *  * format defined by the user (with setRequestFormat())
   *  * sf_format request parameter
   *  * default format from factories
   *
   * @return string The request format
   */
  public function getRequestFormat()
  {
    if (null === $this->format)
    {
      $this->setRequestFormat($this->getParameter('sf_format', $this->getOption('default_format')));
    }

    return $this->format;
  }

  /**
   * Retrieves an array of files.
   *
   * @param  string $key  A key
   * @return array  An associative array of files
   */
  public function getFiles($key = null)
  {
    if (false === $this->fixedFileArray)
    {
      $this->fixedFileArray = self::convertFileInformation($_FILES);
    }

    return null === $key ? $this->fixedFileArray : (isset($this->fixedFileArray[$key]) ? $this->fixedFileArray[$key] : array());
  }

  /**
   * Converts uploaded file array to a format following the $_GET and $POST naming convention.
   *
   * It's safe to pass an already converted array, in which case this method just returns the original array unmodified.
   *
   * @param  array $taintedFiles An array representing uploaded file information
   *
   * @return array An array of re-ordered uploaded file information
   */
  static public function convertFileInformation(array $taintedFiles)
  {
    $files = array();
    foreach ($taintedFiles as $key => $data)
    {
      $files[$key] = self::fixPhpFilesArray($data);
    }

    return $files;
  }

  /**
   * Fixes PHP files array
   *
   * @param array $data The PHP files
   *
   * @return array The fixed PHP files array
   */
  static protected function fixPhpFilesArray(array $data)
  {
    $fileKeys = array('error', 'name', 'size', 'tmp_name', 'type');
    $keys = array_keys($data);
    sort($keys);

    if ($fileKeys != $keys || !isset($data['name']) || !is_array($data['name']))
    {
      return $data;
    }

    $files = $data;
    foreach ($fileKeys as $k)
    {
      unset($files[$k]);
    }
    foreach (array_keys($data['name']) as $key)
    {
      $files[$key] = self::fixPhpFilesArray(array(
        'error'    => $data['error'][$key],
        'name'     => $data['name'][$key],
        'type'     => $data['type'][$key],
        'tmp_name' => $data['tmp_name'][$key],
        'size'     => $data['size'][$key],
      ));
    }

    return $files;
  }

  /**
   * Returns the value of a GET parameter.
   *
   * @param  string $name     The GET parameter name
   * @param  string $default  The default value
   *
   * @return string The GET parameter value
   */
  public function getGetParameter($name, $default = null)
  {
    if (isset($this->getParameters[$name]))
    {
      return $this->getParameters[$name];
    }
    else
    {
      return sfToolkit::getArrayValueForPath($this->getParameters, $name, $default);
    }
  }

  /**
   * Returns the value of a POST parameter.
   *
   * @param  string $name     The POST parameter name
   * @param  string $default  The default value
   *
   * @return string The POST parameter value
   */
  public function getPostParameter($name, $default = null)
  {
    if (isset($this->postParameters[$name]))
    {
      return $this->postParameters[$name];
    }
    else
    {
      return sfToolkit::getArrayValueForPath($this->postParameters, $name, $default);
    }
  }

  /**
   * Returns the value of a parameter passed as a URL segment.
   *
   * @param  string $name     The parameter name
   * @param  string $default  The default value
   *
   * @return string The parameter value
   */
  public function getUrlParameter($name, $default = null)
  {
    if (isset($this->requestParameters[$name]))
    {
      return $this->requestParameters[$name];
    }
    else
    {
      return sfToolkit::getArrayValueForPath($this->requestParameters, $name, $default);
    }
  }

  /**
   * Returns the remote IP address that made the request.
   *
   * @return string The remote IP address
   */
  public function getRemoteAddress()
  {
    $pathInfo = $this->getPathInfoArray();

    return $pathInfo['REMOTE_ADDR'];
  }

  /**
   * Returns an array containing a list of IPs, the first being the client address
   * and the others the addresses of each proxy that passed the request. The address
   * for the last proxy can be retrieved via getRemoteAddress().
   *
   * This method returns null if no proxy passed this request. Note that some proxies
   * do not use this header, and act as if they were the client.
   *
   * @return string|null An array of IP from the client and the proxies that passed
   * the request, or null if no proxy was used.
   */
  public function getForwardedFor()
  {
    $pathInfo = $this->getPathInfoArray();

    if (empty($pathInfo['HTTP_X_FORWARDED_FOR']))
    {
      return null;
    }

    return explode(', ', $pathInfo['HTTP_X_FORWARDED_FOR']);
  }

  /**
   * Returns the client IP address that made the request.
   *
   * @param  boolean $proxy Whether the current request has been made behind a proxy or not
   *
   * @return string Client IP(s)
   */
  public function getClientIp($proxy = true)
  {
    if ($proxy)
    {
      $pathInfo = $this->getPathInfoArray();

      if (isset($pathInfo["HTTP_CLIENT_IP"]) && ($ip = $pathInfo["HTTP_CLIENT_IP"]))
      {
        return $ip;
      }

      if ($this->getOption('trust_proxy') && ($ip = $this->getForwardedFor()))
      {
        return isset($ip[0]) ? trim($ip[0]) : '';
      }
    }

    return $this->getRemoteAddress();
  }

  /**
   * Check CSRF protection
   *
   * @throws <b>sfValidatorErrorSchema</b> If an error occurs while validating the CRF protection for this sfRequest
   */
  public function checkCSRFProtection()
  {
    $form = new BaseForm();
    $form->bind($form->isCSRFProtected() ? array($form->getCSRFFieldName() => $this->getParameter($form->getCSRFFieldName())) : array());

    if (!$form->isValid())
    {
      throw $form->getErrorSchema();
    }
  }

  /**
   * Parses the request parameters.
   *
   * This method notifies the request.filter_parameters event.
   *
   * @return array An array of request parameters.
   */
  protected function parseRequestParameters()
  {
    return $this->dispatcher->filter(new sfEvent($this, 'request.filter_parameters', $this->getRequestContext()), array())->getReturnValue();
  }

  /**
   * Returns the request context used.
   *
   * @return array An array of values representing the current request
   */
  public function getRequestContext()
  {
    return array(
      'path_info'   => $this->getPathInfo(),
      'prefix'      => $this->getPathInfoPrefix(),
      'method'      => $this->getMethod(),
      'format'      => $this->getRequestFormat(),
      'host'        => $this->getHost(),
      'is_secure'   => $this->isSecure(),
      'request_uri' => $this->getUri(),
    );
  }

  /**
   * Move symfony parameters to attributes (parameters prefixed with _sf_)
   */
  protected function fixParameters()
  {
    foreach ($this->parameterHolder->getAll() as $key => $value)
    {
      if (0 === stripos($key, '_sf_'))
      {
        $this->parameterHolder->remove($key);
        $this->setAttribute(substr($key, 1), $value);
      }
    }
  }
}
