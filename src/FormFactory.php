<?php

declare(strict_types=1);

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterInterface;
use const LIBXML_HTML_NODEFDTD;
use const LIBXML_HTML_NOIMPLIED;
use function libxml_use_internal_errors;

final class FormFactory implements FormFactoryInterface
{
    /** @var Factory */
    private $factory;

    /** @var null|array */
    private $options;

    /**
     * @inheritdoc
     */
    public function __construct(?Factory $factory = null, ?array $options = null)
    {
        $this->factory = $factory ?? new Factory();
        $this->options = $options;
    }

    /**
     * @inheritdoc
     */
    public function fromHtml(
        $html,
        ?array $defaultValues = null,
        ?InputFilterInterface $inputFilter = null
    ) : FormInterface {
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
