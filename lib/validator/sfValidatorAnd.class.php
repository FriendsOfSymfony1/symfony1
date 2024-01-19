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
 * sfValidatorAnd validates an input value if all validators passes.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorAnd extends \sfValidatorBase
{
    protected $validators = [];

    /**
     * Constructor.
     *
     * The first argument can be:
     *
     *  * null
     *  * a sfValidatorBase instance
     *  * an array of sfValidatorBase instances
     *
     * @param mixed $validators Initial validators
     * @param array $options    An array of options
     * @param array $messages   An array of error messages
     *
     * @see \sfValidatorBase
     */
    public function __construct($validators = null, $options = [], $messages = [])
    {
        if ($validators instanceof \sfValidatorBase) {
            $this->addValidator($validators);
        } elseif (is_array($validators)) {
            foreach ($validators as $validator) {
                $this->addValidator($validator);
            }
        } elseif (null !== $validators) {
            throw new \InvalidArgumentException('sfValidatorAnd constructor takes a sfValidatorBase object, or a sfValidatorBase array.');
        }

        parent::__construct($options, $messages);
    }

    /**
     * Adds a validator.
     *
     * @param \sfValidatorBase $validator A sfValidatorBase instance
     */
    public function addValidator(\sfValidatorBase $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * Returns an array of the validators.
     *
     * @return array An array of sfValidatorBase instances
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @see \sfValidatorBase
     */
    public function asString($indent = 0)
    {
        $validators = '';
        for ($i = 0, $max = count($this->validators); $i < $max; ++$i) {
            $validators .= "\n".$this->validators[$i]->asString($indent + 2)."\n";

            if ($i < $max - 1) {
                $validators .= str_repeat(' ', $indent + 2).'and';
            }

            if ($i == $max - 2) {
                $options = $this->getOptionsWithoutDefaults();
                $messages = $this->getMessagesWithoutDefaults();

                if ($options || $messages) {
                    $validators .= sprintf(
                        '(%s%s)',
                        $options ? \sfYamlInline::dump($options) : ($messages ? '{}' : ''),
                        $messages ? ', '.\sfYamlInline::dump($messages) : ''
                    );
                }
            }
        }

        return sprintf('%s(%s%s)', str_repeat(' ', $indent), $validators, str_repeat(' ', $indent));
    }

    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * halt_on_error: Whether to halt on the first error or not (false by default)
     *
     * @param array $options  An array of options
     * @param array $messages An array of error messages
     *
     * @see \sfValidatorBase
     */
    protected function configure($options = [], $messages = [])
    {
        $this->addOption('halt_on_error', false);

        $this->setMessage('invalid', null);
    }

    /**
     * @see \sfValidatorBase
     */
    protected function doClean($value)
    {
        $clean = $value;
        $errors = new \sfValidatorErrorSchema($this);
        foreach ($this->validators as $validator) {
            try {
                $clean = $validator->clean($clean);
            } catch (\sfValidatorError $e) {
                $errors->addError($e);

                if ($this->getOption('halt_on_error')) {
                    break;
                }
            }
        }

        if ($errors->count()) {
            if ($this->getMessage('invalid')) {
                throw new \sfValidatorError($this, 'invalid', ['value' => $value]);
            }

            throw $errors;
        }

        return $clean;
    }
}
