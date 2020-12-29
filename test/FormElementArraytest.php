<?php

declare(strict_types=1);

namespace XtreamwayzTest\HTMLFormValidator;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface;
use Xtreamwayz\HTMLFormValidator\FormFactory;
use Xtreamwayz\HTMLFormValidator\ValidationResult;

use function preg_replace;

use const LIBXML_HTML_NODEFDTD;
use const LIBXML_HTML_NOIMPLIED;
use const LIBXML_NOBLANKS;

class FormElementArraytest extends TestCase
{
    use ProphecyTrait;

    public function testCheckboxArrayIsValid(): void
    {
        $html = '
            <form action="/" method="post">
                <input type="checkbox" name="cars[]" value="audi" data-reuse-submitted-value="true" />
                <input type="checkbox" name="cars[]" value="bmw" data-reuse-submitted-value="true" />
                <input type="checkbox" name="cars[]" value="volkswagen" data-reuse-submitted-value="true" />
            </form>';

        $form    = (new FormFactory())->fromHtml($html);
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getMethod()->willReturn('POST');
        $request->getParsedBody()->willReturn([
            'cars' => ['audi', 'bmw'],
        ]);

        $result = $form->validateRequest($request->reveal());

        self::assertInstanceOf(ValidationResult::class, $result);
        self::assertEquals(['cars' => ['audi', 'bmw']], $result->getValues());
        self::assertEquals([], $result->getMessages());
        self::assertTrue($result->isValid());

        $expected = '
            <form action="/" method="post">
                <input type="checkbox" name="cars[]" value="audi" checked />
                <input type="checkbox" name="cars[]" value="bmw" checked />
                <input type="checkbox" name="cars[]" value="volkswagen" />
            </form>';

        $actual = $form->asString($result);
        self::assertEquals(
            $this->getDomDocument($expected),
            $this->getDomDocument($actual),
            'Failed asserting that the form is rendered correctly.'
        );
    }

    private function getDomDocument(string $html): string
    {
        $doc = new DOMDocument('1.0', 'utf-8');

        $doc->preserveWhiteSpace = false;
        // Don't add missing doctype, html and body
        //libxml_use_internal_errors(true);
        $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOBLANKS);
        //libxml_use_internal_errors(false);
        // Remove whitespace for better comparison

        return preg_replace('~\s+~i', ' ', $doc->saveHTML());
    }
}
