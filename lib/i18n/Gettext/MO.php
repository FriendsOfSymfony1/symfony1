<?php

// +----------------------------------------------------------------------+
// | PEAR :: File :: Gettext :: MO                                        |
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
 * File::Gettext::MO.
 *
 * @author      Michael Wallner <mike@php.net>
 * @license     PHP License
 */
require_once __DIR__.'/TGettext.class.php';

/**
 * File_Gettext_MO.
 *
 * GNU MO file reader and writer.
 *
 * @author      Michael Wallner <mike@php.net>
 *
 * @version     $Revision: 9856 $
 */
class TGettext_MO extends TGettext
{
    /**
     * file handle.
     *
     * @var resource
     */
    protected $_handle;

    /**
     * big endianess.
     *
     * Whether to write with big endian byte order.
     *
     * @var bool
     */
    protected $writeBigEndian = false;

    /**
     * Constructor.
     *
     * @param string $file path to GNU MO file
     *
     * @return object File_Gettext_MO
     */
    public function __construct($file = '')
    {
        $this->file = $file;
    }

    /**
     * _read.
     *
     * @param int $bytes
     */
    public function _read($bytes = 1)
    {
        if (0 < $bytes = abs($bytes)) {
            return fread($this->_handle, $bytes);
        }

        return null;
    }

    /**
     * _readInt.
     *
     * @param bool $bigendian
     *
     * @return int
     */
    public function _readInt($bigendian = false)
    {
        // unpack returns a reference????
        $unpacked = unpack($bigendian ? 'N' : 'V', $this->_read(4));

        return array_shift($unpacked);
    }

    /**
     * _writeInt.
     *
     * @param int $int
     *
     * @return int
     */
    public function _writeInt($int)
    {
        return $this->_write(pack($this->writeBigEndian ? 'N' : 'V', (int) $int));
    }

    /**
     * _write.
     *
     * @param string $data
     *
     * @return int
     */
    public function _write($data)
    {
        return fwrite($this->_handle, $data);
    }

    /**
     * _writeStr.
     *
     * @param string $string
     *
     * @return int
     */
    public function _writeStr($string)
    {
        return $this->_write($string."\0");
    }

    /**
     * _readStr.
     *
     * @param array $params associative array with offset and length
     *                      of the string
     *
     * @return string
     */
    public function _readStr($params)
    {
        fseek($this->_handle, $params['offset']);

        return $this->_read($params['length']);
    }

    /**
     * Load MO file.
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

        // open MO file
        if (!is_resource($this->_handle = @fopen($file, 'rb'))) {
            return false;
        }
        // lock MO file shared
        if (!@flock($this->_handle, LOCK_SH)) {
            @fclose($this->_handle);

            return false;
        }

        // read (part of) magic number from MO file header and define endianess

        // unpack returns a reference????
        $unpacked = unpack('c', $this->_read(4));

        switch ($magic = array_shift($unpacked)) {
            case -34:
                $be = false;

                break;

            case -107:
                $be = true;

                break;

            default:
                return false;
        }

        // check file format revision - we currently only support 0
        if (0 !== ($_rev = $this->_readInt($be))) {
            return false;
        }

        // count of strings in this file
        $count = $this->_readInt($be);

        // offset of hashing table of the msgids
        $offset_original = $this->_readInt($be);
        // offset of hashing table of the msgstrs
        $offset_translat = $this->_readInt($be);

        // move to msgid hash table
        fseek($this->_handle, $offset_original);
        // read lengths and offsets of msgids
        $original = [];
        for ($i = 0; $i < $count; ++$i) {
            $original[$i] = [
                'length' => $this->_readInt($be),
                'offset' => $this->_readInt($be),
            ];
        }

        // move to msgstr hash table
        fseek($this->_handle, $offset_translat);
        // read lengths and offsets of msgstrs
        $translat = [];
        for ($i = 0; $i < $count; ++$i) {
            $translat[$i] = [
                'length' => $this->_readInt($be),
                'offset' => $this->_readInt($be),
            ];
        }

        // read all
        for ($i = 0; $i < $count; ++$i) {
            $this->strings[$this->_readStr($original[$i])] =
                $this->_readStr($translat[$i]);
        }

        // done
        @flock($this->_handle, LOCK_UN);
        @fclose($this->_handle);
        $this->_handle = null;

        // check for meta info
        if (isset($this->strings[''])) {
            $this->meta = parent::meta2array($this->strings['']);
            unset($this->strings['']);
        }

        return true;
    }

    /**
     * Save MO file.
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

        // open MO file
        if (!is_resource($this->_handle = @fopen($file, 'wb'))) {
            return false;
        }
        // lock MO file exclusively
        if (!@flock($this->_handle, LOCK_EX)) {
            @fclose($this->_handle);

            return false;
        }

        // write magic number
        if ($this->writeBigEndian) {
            $this->_write(pack('c*', 0x95, 0x04, 0x12, 0xDE));
        } else {
            $this->_write(pack('c*', 0xDE, 0x12, 0x04, 0x95));
        }

        // write file format revision
        $this->_writeInt(0);

        $count = count($this->strings) + ($meta = (count($this->meta) ? 1 : 0));
        // write count of strings
        $this->_writeInt($count);

        $offset = 28;
        // write offset of orig. strings hash table
        $this->_writeInt($offset);

        $offset += ($count * 8);
        // write offset transl. strings hash table
        $this->_writeInt($offset);

        // write size of hash table (we currently ommit the hash table)
        $this->_writeInt(0);

        $offset += ($count * 8);
        // write offset of hash table
        $this->_writeInt($offset);

        // unshift meta info
        if ($meta) {
            $meta = '';
            foreach ($this->meta as $key => $val) {
                $meta .= $key.': '.$val."\n";
            }
            $strings = ['' => $meta] + $this->strings;
        } else {
            $strings = $this->strings;
        }

        // write offsets for original strings
        foreach (array_keys($strings) as $o) {
            $len = strlen($o);
            $this->_writeInt($len);
            $this->_writeInt($offset);
            $offset += $len + 1;
        }

        // write offsets for translated strings
        foreach ($strings as $t) {
            $len = strlen($t);
            $this->_writeInt($len);
            $this->_writeInt($offset);
            $offset += $len + 1;
        }

        // write original strings
        foreach (array_keys($strings) as $o) {
            $this->_writeStr($o);
        }

        // write translated strings
        foreach ($strings as $t) {
            $this->_writeStr($t);
        }

        // done
        @flock($this->_handle, LOCK_UN);
        @fclose($this->_handle);

        return true;
    }
}
