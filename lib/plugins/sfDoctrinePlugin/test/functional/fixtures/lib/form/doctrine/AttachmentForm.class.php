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
 * Attachment form.
 *
 * @author     Your name here
 *
 * @version    SVN: $Id$
 */
class AttachmentForm extends \BaseAttachmentForm
{
    public const TEST_GENERATED_FILENAME = 'test123';

    public function configure()
    {
        $this->widgetSchema['file_path'] = new \sfWidgetFormInputFileEditable([
            'file_src' => \sfConfig::get('sf_cache_dir').'/'.$this->getObject()->file_path,
            'edit_mode' => !$this->isNew(),
        ]);
        $this->validatorSchema['file_path'] = new \sfValidatorFile([
            'path' => \sfConfig::get('sf_cache_dir'),
            'mime_type_guessers' => [],
            'required' => false,
        ]);
        $this->validatorSchema['file_path_delete'] = new \sfValidatorBoolean();
    }

    protected function generateFilePathFilename()
    {
        return self::TEST_GENERATED_FILENAME;
    }
}
