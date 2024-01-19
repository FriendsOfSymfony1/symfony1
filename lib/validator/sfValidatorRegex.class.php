<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorRegex validates a value with a regular expression.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorRegex extends sfValidatorString
{
    /**
     * Returns the current validator's regular expression.
     *
     * @return string
     */
    public function getPattern()
    {
        $pattern = $this->getOption('pattern');

        return $pattern instanceof sfCallable ? $pattern->call() : $pattern;
    }

    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * pattern:    A regex pattern compatible with PCRE or {@link sfCallable} that returns one (required)
     *  * must_match: Whether the regex must match or not (true by default)
     *
     * @param array $options  An array of options
     * @param array $messages An array of error messages
     *
     * @see sfValidatorString
     */
    protected function configure($options = [], $messages = [])
    {
        parent::configure($options, $messages);

        $this->addRequiredOption('pattern');
        $this->addOption('must_match', true);
    }

    /**
     * @see sfValidatorString
     *
     * @param mixed $value
     */
    protected function doClean($value)
    {
        $clean = parent::doClean($value);

        $pattern = $this->getPattern();

        if (
            ($this->getOption('must_match') && !preg_match($pattern, $clean))
            || (!$this->getOption('must_match') && preg_match($pattern, $clean))
        ) {
            throw new sfValidatorError($this, 'invalid', ['value' => $value]);
        }

        return $clean;
    }
}
