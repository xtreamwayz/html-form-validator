<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Zend\InputFilter\FileInput;
use Zend\Validator\File\MimeType as MimeTypeValidator;

class File extends BaseFormElement
{
    public function getInputSpecification()
    {
        $spec = [
            'type'     => FileInput::class,
            'name'     => $this->getName(),
            'required' => $this->isRequired(),
        ];

        if ($this->node->hasAttribute('accept')) {
            $spec['validators'] = [
                [
                    'name'    => MimeTypeValidator::class,
                    'options' => [
                        'mimeType'          => $this->node->getAttribute('accept'),
                        'enableHeaderCheck' => true,
                    ],
                ],
            ];
        }

        return $spec;
    }
}
