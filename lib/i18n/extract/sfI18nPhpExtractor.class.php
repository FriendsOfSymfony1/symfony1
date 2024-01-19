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
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfI18nPhpExtractor implements \sfI18nExtractorInterface
{
    /**
     * Extract i18n strings for the given content.
     *
     * @param string $content The content
     *
     * @return array An array of i18n strings
     */
    public function extract($content)
    {
        $tokens = token_get_all($content);

        $strings = [];
        $i18n_function = 0;
        $line = 0;
        $heredoc = false;
        $buffer = '';
        foreach ($tokens as $token) {
            if (is_string($token)) {
                switch ($token) {
                    case '(':
                        if (1 == $i18n_function) {
                            $i18n_function = 2;
                        }

                        break;

                    default:
                        $i18n_function = 0;
                }
            } else {
                list($id, $text) = $token;

                switch ($id) {
                    case T_STRING:
                        if ($heredoc && 2 == $i18n_function) {
                            $buffer .= $text;
                        } else {
                            $i18n_function = ('__' == $text || 'format_number_choice' == $text) ? 1 : 0;
                        }

                        break;

                    case T_WHITESPACE:
                        break;

                    case T_START_HEREDOC:
                        $heredoc = true;

                        break;

                    case T_END_HEREDOC:
                        $heredoc = false;
                        if ($buffer) {
                            $strings[] = $buffer;
                            $buffer = '';
                        }
                        $i18n_function = 0;

                        break;

                    case T_CONSTANT_ENCAPSED_STRING:
                        if (2 == $i18n_function) {
                            $delimiter = $text[0];
                            $strings[] = str_replace('\\'.$delimiter, $delimiter, substr($text, 1, -1));
                        }
                        $i18n_function = 0;

                        break;

                    default:
                        if ($heredoc && 2 == $i18n_function) {
                            $buffer .= $text;
                        } else {
                            $i18n_function = 0;
                        }
                }
            }
        }

        return $strings;
    }
}
