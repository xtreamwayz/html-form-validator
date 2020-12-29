<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilterInterface;

use function libxml_use_internal_errors;

use const LIBXML_HTML_NODEFDTD;
use const LIBXML_HTML_NOIMPLIED;

final class FormFactory implements FormFactoryInterface
{
    /** @var Factory */
    private $factory;

    /** @var null|array */
    private $options;

    /**
     * @inheritDoc
     */
    public function __construct(?Factory $factory = null, ?array $options = null)
    {
        $this->factory = $factory ?? new Factory();
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function fromHtml(
        $html,
        ?array $defaultValues = null,
        ?InputFilterInterface $inputFilter = null
    ): FormInterface {
        // Create new doc
        $document = new DOMDocument('1.0', 'utf-8');

        // Ignore invalid tag errors during loading (e.g. datalist)
        libxml_use_internal_errors(true);
        // Enforce UTF-8 encoding and don't add missing doctype, html and body
        $document->loadHTML(
            '<?xml version="1.0" encoding="UTF-8"?>' . $html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_use_internal_errors(false);

        return new Form(
            $document,
            $defaultValues ?? [],
            $this->factory,
            $inputFilter ?? $this->factory->createInputFilter([]),
            $this->options
        );
    }
}
