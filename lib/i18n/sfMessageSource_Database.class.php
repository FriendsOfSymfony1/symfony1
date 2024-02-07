<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfMessageSource_Database class.
 *
 * This is the base class for database based message sources like MySQL or SQLite.
 *
 * @author Xiang Wei Zhuo <weizhuo[at]gmail[dot]com>
 *
 * @version v1.0, last update on Fri Dec 24 16:18:44 EST 2004
 */
abstract class sfMessageSource_Database extends \sfMessageSource
{
    /**
     * Gets all the variants of a particular catalogue.
     *
     * @param string $catalogue catalogue name
     *
     * @return array list of all variants for this catalogue
     */
    public function getCatalogueList($catalogue)
    {
        $variants = explode('_', $this->culture);

        $catalogues = [$catalogue];

        $variant = null;

        for ($i = 0, $max = count($variants); $i < $max; ++$i) {
            if (strlen($variants[$i]) > 0) {
                $variant .= $variant ? '_'.$variants[$i] : $variants[$i];
                $catalogues[] = $catalogue.'.'.$variant;
            }
        }

        return array_reverse($catalogues);
    }

    public function getId()
    {
        return md5($this->source);
    }

    /**
     * For a given DSN (database connection string), return some information about the DSN.
     *
     * This function comes from PEAR's DB package.
     *
     * @param string $dsn DSN format, similar to PEAR's DB
     *
     * @return array DSN information
     */
    protected function parseDSN($dsn)
    {
        if (is_array($dsn)) {
            return $dsn;
        }

        $parsed = [
            'phptype' => false,
            'dbsyntax' => false,
            'username' => false,
            'password' => false,
            'protocol' => false,
            'hostspec' => false,
            'port' => false,
            'socket' => false,
            'database' => false,
        ];

        // Find phptype and dbsyntax
        if (($pos = strpos($dsn, '://')) !== false) {
            $str = substr($dsn, 0, $pos);
            $dsn = substr($dsn, $pos + 3);
        } else {
            $str = $dsn;
            $dsn = null;
        }

        // Get phptype and dbsyntax
        // $str => phptype(dbsyntax)
        if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
            $parsed['phptype'] = $arr[1];
            $parsed['dbsyntax'] = (empty($arr[2])) ? $arr[1] : $arr[2];
        } else {
            $parsed['phptype'] = $str;
            $parsed['dbsyntax'] = $str;
        }

        if (empty($dsn)) {
            return $parsed;
        }

        // Get (if found): username and password
        // $dsn => username:password@protocol+hostspec/database
        if (($at = strrpos($dsn, '@')) !== false) {
            $str = substr($dsn, 0, $at);
            $dsn = substr($dsn, $at + 1);
            if (($pos = strpos($str, ':')) !== false) {
                $parsed['username'] = rawurldecode(substr($str, 0, $pos));
                $parsed['password'] = rawurldecode(substr($str, $pos + 1));
            } else {
                $parsed['username'] = rawurldecode($str);
            }
        }

        // Find protocol and hostspec

        // $dsn => proto(proto_opts)/database
        if (preg_match('|^([^(]+)\((.*?)\)/?(.*?)$|', $dsn, $match)) {
            $proto = $match[1];
            $proto_opts = (!empty($match[2])) ? $match[2] : false;
            $dsn = $match[3];
            // $dsn => protocol+hostspec/database (old format)
        } else {
            if (str_contains($dsn, '+')) {
                list($proto, $dsn) = explode('+', $dsn, 2);
            }
            if (str_contains($dsn, '/')) {
                list($proto_opts, $dsn) = explode('/', $dsn, 2);
            } else {
                $proto_opts = $dsn;
                $dsn = null;
            }
        }

        // process the different protocol options
        $parsed['protocol'] = (!empty($proto)) ? $proto : 'tcp';
        $proto_opts = rawurldecode($proto_opts);
        if ('tcp' == $parsed['protocol']) {
            if (str_contains($proto_opts, ':')) {
                list($parsed['hostspec'], $parsed['port']) = explode(':', $proto_opts);
            } else {
                $parsed['hostspec'] = $proto_opts;
            }
        } elseif ('unix' == $parsed['protocol']) {
            $parsed['socket'] = $proto_opts;
        }

        // Get dabase if any
        // $dsn => database
        if (!empty($dsn)) {
            // /database
            if (($pos = strpos($dsn, '?')) === false) {
                $parsed['database'] = $dsn;
                // /database?param1=value1&param2=value2
            } else {
                $parsed['database'] = substr($dsn, 0, $pos);
                $dsn = substr($dsn, $pos + 1);
                if (str_contains($dsn, '&')) {
                    $opts = explode('&', $dsn);
                } else { // database?param1=value1
                    $opts = [$dsn];
                }
                foreach ($opts as $opt) {
                    list($key, $value) = explode('=', $opt);
                    if (!isset($parsed[$key])) { // don't allow params overwrite
                        $parsed[$key] = rawurldecode($value);
                    }
                }
            }
        }

        return $parsed;
    }
}
