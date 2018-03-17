<?php

declare(strict_types=1);

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
