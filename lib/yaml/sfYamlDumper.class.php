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
 * sfYamlDumper dumps PHP variables to YAML strings.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id: sfYamlDumper.class.php 10575 2008-08-01 13:08:42Z nicolas $
 */
class sfYamlDumper
{
    /**
     * Dumps a PHP value to YAML.
     *
     * @param mixed $input  The PHP value
     * @param int   $inline The level where you switch to inline YAML
     * @param int   $indent The level o indentation indentation (used internally)
     *
     * @return string The YAML representation of the PHP value
     */
    public function dump($input, $inline = 0, $indent = 0)
    {
        $output = '';
        $prefix = $indent ? str_repeat(' ', $indent) : '';

        if ($inline <= 0 || !is_array($input) || empty($input)) {
            $output .= $prefix.\sfYamlInline::dump($input);
        } else {
            $isAHash = array_keys($input) !== range(0, count($input) - 1);

            foreach ($input as $key => $value) {
                $willBeInlined = $inline - 1 <= 0 || !is_array($value) || empty($value);

                $output .= sprintf(
                    '%s%s%s%s',
                    $prefix,
                    $isAHash ? \sfYamlInline::dump($key).':' : '-',
                    $willBeInlined ? ' ' : "\n",
                    $this->dump($value, $inline - 1, $willBeInlined ? 0 : $indent + 2)
                ).($willBeInlined ? "\n" : '');
            }
        }

        return $output;
    }
}
