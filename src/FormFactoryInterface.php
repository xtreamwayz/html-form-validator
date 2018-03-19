<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use Psr\Http\Message\StreamInterface;
use Zend\InputFilter\InputFilterInterface;

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
    ) : FormInterface;
}
