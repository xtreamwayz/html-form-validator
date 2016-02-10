<?php

namespace XtreamwayzTest\HTMLFormValidator\FormElement;

use ReflectionMethod;
use Xtreamwayz\HTMLFormValidator\FormElement\Text;

class AbstractFormElementTest extends \PHPUnit_Framework_TestCase
{
    public function dataAttributesProvider()
    {
        return [
            'single-filter'                               => [
                'stringtrim',
                [
                    'stringtrim' => [],
                ],
            ],
            'multiple-filters'                            => [
                'stringtrim|alpha',
                [
                    'stringtrim' => [],
                    'alpha'      => [],
                ],
            ],
            'single-option'                               => [
                'identical{token:password}',
                [
                    'identical' => [
                        'token' => 'password',
                    ],
                ],
            ],
            'multiple-options'                            => [
                'stringlength{min:2,max:140}',
                [
                    'stringlength' => [
                        'min' => '2',
                        'max' => '140',
                    ],
                ],
            ],
            'options-with-spaces'                         => [
                'validator{key: va l ue , foo :  bar  , baz:qux }',
                [
                    'validator' => [
                        'key' => 'va l ue',
                        'foo' => 'bar',
                        'baz' => 'qux',
                    ],
                ],
            ],
            'options-with-double-quotes'                  => [
                'validator{key: "va l ue" , "foo" : " bar ", "baz": qux }',
                [
                    'validator' => [
                        'key' => 'va l ue',
                        'foo' => 'bar',
                        'baz' => 'qux',
                    ],
                ],
            ],
            'options-with-single-quotes'                  => [
                "validator{key: 'va l ue' , 'foo' : ' bar ', 'baz': qux }",
                [
                    'validator' => [
                        'key' => 'va l ue',
                        'foo' => 'bar',
                        'baz' => 'qux',
                    ],
                ],
            ],
            'multiple-options-multiple-quotes-and-spaces' => [
                'validator{key: "va l ue" , \'foo\' : " bar ", \'baz\': q.u.x. }',
                [
                    'validator' => [
                        'key' => 'va l ue',
                        'foo' => 'bar',
                        'baz' => 'q.u.x.',
                    ],
                ],
            ],
            'multiple-options-and-validators'             => [
                'stringlength{min:2,max:140}|validator{key:val,foo:bar}|notempty',
                [
                    'stringlength' => [
                        'min' => '2',
                        'max' => '140',
                    ],
                    'validator'    => [
                        'key' => 'val',
                        'foo' => 'bar',
                    ],
                    'notempty'     => [
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataAttributesProvider
     */
    public function testParseDataAttribute($dataAttribute, $expected)
    {
        $reflectionMethod = new ReflectionMethod(Text::class, 'parseDataAttribute');
        $reflectionMethod->setAccessible(true);
        $actual = iterator_to_array($reflectionMethod->invokeArgs(new Text(), [$dataAttribute]));

        $this->assertEquals($expected, $actual);
    }
}
