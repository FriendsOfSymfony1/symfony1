<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorOr validates an input value if at least one validator passes.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 */
class sfValidatorOr extends sfValidatorBase
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
     * @see sfValidatorBase
     */
    public function __construct($validators = null, $options = [], $messages = [])
    {
        if ($validators instanceof sfValidatorBase) {
            $this->addValidator($validators);
        } elseif (is_array($validators)) {
            foreach ($validators as $validator) {
                $this->addValidator($validator);
            }
        } elseif (null !== $validators) {
            throw new InvalidArgumentException('sfValidatorOr constructor takes a sfValidatorBase object, or a sfValidatorBase array.');
        }

        parent::__construct($options, $messages);
    }

    /**
     * Adds a validator.
     *
     * @param sfValidatorBase $validator An sfValidatorBase instance
     */
    public function addValidator(sfValidatorBase $validator)
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
     * @see sfValidatorBase
     *
     * @param mixed $indent
     */
    public function asString($indent = 0)
    {
        $validators = '';
        for ($i = 0, $max = count($this->validators); $i < $max; ++$i) {
            $validators .= "\n".$this->validators[$i]->asString($indent + 2)."\n";

            if ($i < $max - 1) {
                $validators .= str_repeat(' ', $indent + 2).'or';
            }

            if ($i == $max - 2) {
                $options = $this->getOptionsWithoutDefaults();
                $messages = $this->getMessagesWithoutDefaults();

                if ($options || $messages) {
                    $validators .= sprintf(
                        '(%s%s)',
                        $options ? sfYamlInline::dump($options) : ($messages ? '{}' : ''),
                        $messages ? ', '.sfYamlInline::dump($messages) : ''
                    );
                }
            }
        }

        return sprintf('%s(%s%s)', str_repeat(' ', $indent), $validators, str_repeat(' ', $indent));
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $options
     * @param mixed $messages
     */
    protected function configure($options = [], $messages = [])
    {
        $this->setMessage('invalid', null);
    }

    /**
     * @see sfValidatorBase
     *
     * @param mixed $value
     */
    protected function doClean($value)
    {
        $errors = new sfValidatorErrorSchema($this);
        foreach ($this->validators as $validator) {
            try {
                return $validator->clean($value);
            } catch (sfValidatorError $e) {
                $errors->addError($e);
            }
        }

        if ($this->getMessage('invalid')) {
            throw new sfValidatorError($this, 'invalid', ['value' => $value]);
        }

        throw $errors;
    }
}
