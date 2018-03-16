<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator;

use Zend\InputFilter\InputFilterInterface;

interface FormFactoryInterface
{
    /**
     * Load html form and optionally set default data
     */
    public function fromHtml(
        string $htmlForm,
        ?array $defaultValues = null,
        ?InputFilterInterface $inputFilter = null
    ) : FormInterface;
}
