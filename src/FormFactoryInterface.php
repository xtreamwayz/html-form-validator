<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\StreamInterface;

interface FormFactoryInterface
{
    /**
     * Load html form and optionally set default data
     *
     * @param string|resource|StreamInterface $html
     */
    public function fromHtml(
        $html,
        ?array $defaultValues = null,
        ?InputFilterInterface $inputFilter = null
    ): FormInterface;
}
