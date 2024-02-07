<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// | PEAR :: File :: Gettext :: PO                                        |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is available at http://www.php.net/license/3_0.txt              |
// | If you did not receive a copy of the PHP license and are unable      |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Michael Wallner <mike@iworks.at>                  |
// +----------------------------------------------------------------------+
//
// $Id$

/**
 * File::Gettext::PO.
 *
 * @author      Michael Wallner <mike@php.net>
 * @license     PHP License
 */
require_once __DIR__.'/TGettext.class.php';

/**
 * File_Gettext_PO.
 *
 * GNU PO file reader and writer.
 *
 * @author      Michael Wallner <mike@php.net>
 *
 * @version     $Revision: 9856 $
 */
class TGettext_PO extends \TGettext
{
    /**
     * Constructor.
     *
     * @param   string      path to GNU PO file
     *
     * @return object File_Gettext_PO
     */
    public function __construct($file = '')
    {
        $this->file = $file;
    }

    /**
     * Load PO file.
     *
     * @param string $file
     *
     * @return mixed returns true on success or PEAR_Error on failure
     */
    public function load($file = null)
    {
        if (!isset($file)) {
            $file = $this->file;
        }

        // load file
        if (!$contents = @file($file)) {
            return false;
        }
        $contents = implode('', $contents);

        // match all msgid/msgstr entries
        $matched = preg_match_all(
            '/(msgid\s+("([^"]|\\\\")*?"\s*)+)\s+'.
            '(msgstr\s+("([^"]|\\\\")*?"\s*)+)/',
            $contents,
            $matches
        );
        unset($contents);

        if (!$matched) {
            return false;
        }

        // get all msgids and msgtrs
        for ($i = 0; $i < $matched; ++$i) {
            $msgid = preg_replace(
                '/\s*msgid\s*"(.*)"\s*/s',
                '\\1',
                $matches[1][$i]
            );
            $msgstr = preg_replace(
                '/\s*msgstr\s*"(.*)"\s*/s',
                '\\1',
                $matches[4][$i]
            );
            $this->strings[parent::prepare($msgid)] = parent::prepare($msgstr);
        }

        // check for meta info
        if (isset($this->strings[''])) {
            $this->meta = parent::meta2array($this->strings['']);
            unset($this->strings['']);
        }

        return true;
    }

    /**
     * Save PO file.
     *
     * @param string $file
     *
     * @return mixed returns true on success or PEAR_Error on failure
     */
    public function save($file = null)
    {
        if (!isset($file)) {
            $file = $this->file;
        }

        // open PO file
        if (!is_resource($fh = @fopen($file, 'w'))) {
            return false;
        }

        // lock PO file exclusively
        if (!flock($fh, LOCK_EX)) {
            fclose($fh);

            return false;
        }
        // write meta info
        if (count($this->meta)) {
            $meta = 'msgid ""'."\nmsgstr ".'""'."\n";
            foreach ($this->meta as $k => $v) {
                $meta .= '"'.$k.': '.$v.'\n"'."\n";
            }
            fwrite($fh, $meta."\n");
        }
        // write strings
        foreach ($this->strings as $o => $t) {
            fwrite(
                $fh,
                'msgid "'.parent::prepare($o, true).'"'."\n".
                'msgstr "'.parent::prepare($t, true).'"'."\n\n"
            );
        }

        // done
        @flock($fh, LOCK_UN);
        @fclose($fh);

        return true;
    }
}
