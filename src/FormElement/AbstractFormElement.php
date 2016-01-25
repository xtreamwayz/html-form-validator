<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use DOMElement;
use Zend\Filter;
use Zend\InputFilter\InputInterface;
use Zend\Validator;

abstract class AbstractFormElement
{
    /**
     * Default set of filters
     *
     * TODO: This is a hack to get the input filter with servicemanger 3 working.
     * TODO: Needs to fixed when zend-inputfilter 3 is released.
     *
     * @var array
     */
    protected $filters = [
        'alnum'                      => 'Zend\I18n\Filter\Alnum',
        'alpha'                      => 'Zend\I18n\Filter\Alpha',
        'basename'                   => 'Zend\Filter\BaseName',
        'blacklist'                  => 'Zend\Filter\Blacklist',
        'boolean'                    => 'Zend\Filter\Boolean',
        'callback'                   => 'Zend\Filter\Callback',
        'compress'                   => 'Zend\Filter\Compress',
        'compressbz2'                => 'Zend\Filter\Compress\Bz2',
        'compressgz'                 => 'Zend\Filter\Compress\Gz',
        'compresslzf'                => 'Zend\Filter\Compress\Lzf',
        'compressrar'                => 'Zend\Filter\Compress\Rar',
        'compresssnappy'             => 'Zend\Filter\Compress\Snappy',
        'compresstar'                => 'Zend\Filter\Compress\Tar',
        'compresszip'                => 'Zend\Filter\Compress\Zip',
        'dataunitformatter'          => 'Zend\Filter\DataUnitFormatter',
        'dateselect'                 => 'Zend\Filter\DateSelect',
        'datetimeformatter'          => 'Zend\Filter\DateTimeFormatter',
        'datetimeselect'             => 'Zend\Filter\DateTimeSelect',
        'decompress'                 => 'Zend\Filter\Decompress',
        'decrypt'                    => 'Zend\Filter\Decrypt',
        'digits'                     => 'Zend\Filter\Digits',
        'dir'                        => 'Zend\Filter\Dir',
        'encrypt'                    => 'Zend\Filter\Encrypt',
        'encryptblockcipher'         => 'Zend\Filter\Encrypt\BlockCipher',
        'encryptopenssl'             => 'Zend\Filter\Encrypt\Openssl',
        'filedecrypt'                => 'Zend\Filter\File\Decrypt',
        'fileencrypt'                => 'Zend\Filter\File\Encrypt',
        'filelowercase'              => 'Zend\Filter\File\LowerCase',
        'filerename'                 => 'Zend\Filter\File\Rename',
        'filerenameupload'           => 'Zend\Filter\File\RenameUpload',
        'fileuppercase'              => 'Zend\Filter\File\UpperCase',
        'htmlentities'               => 'Zend\Filter\HtmlEntities',
        'inflector'                  => 'Zend\Filter\Inflector',
        'int'                        => 'Zend\Filter\ToInt',
        'monthselect'                => 'Zend\Filter\MonthSelect',
        'null'                       => 'Zend\Filter\ToNull',
        'numberformat'               => 'Zend\I18n\Filter\NumberFormat',
        'numberparse'                => 'Zend\I18n\Filter\NumberParse',
        'pregreplace'                => 'Zend\Filter\PregReplace',
        'realpath'                   => 'Zend\Filter\RealPath',
        'stringtolower'              => 'Zend\Filter\StringToLower',
        'stringtoupper'              => 'Zend\Filter\StringToUpper',
        'stringtrim'                 => 'Zend\Filter\StringTrim',
        'stripnewlines'              => 'Zend\Filter\StripNewlines',
        'striptags'                  => 'Zend\Filter\StripTags',
        'toint'                      => 'Zend\Filter\ToInt',
        'tonull'                     => 'Zend\Filter\ToNull',
        'urinormalize'               => 'Zend\Filter\UriNormalize',
        'whitelist'                  => 'Zend\Filter\Whitelist',
        'wordcamelcasetodash'        => 'Zend\Filter\Word\CamelCaseToDash',
        'wordcamelcasetoseparator'   => 'Zend\Filter\Word\CamelCaseToSeparator',
        'wordcamelcasetounderscore'  => 'Zend\Filter\Word\CamelCaseToUnderscore',
        'worddashtocamelcase'        => 'Zend\Filter\Word\DashToCamelCase',
        'worddashtoseparator'        => 'Zend\Filter\Word\DashToSeparator',
        'worddashtounderscore'       => 'Zend\Filter\Word\DashToUnderscore',
        'wordseparatortocamelcase'   => 'Zend\Filter\Word\SeparatorToCamelCase',
        'wordseparatortodash'        => 'Zend\Filter\Word\SeparatorToDash',
        'wordunderscoretocamelcase'  => 'Zend\Filter\Word\UnderscoreToCamelCase',
        'wordunderscoretostudlycase' => 'Zend\Filter\Word\UnderscoreToStudlyCase',
        'wordunderscoretodash'       => 'Zend\Filter\Word\UnderscoreToDash',
        'wordunderscoretoseparator'  => 'Zend\Filter\Word\UnderscoreToSeparator',
    ];

    public function __invoke(DOMElement $element, InputInterface $input)
    {
        // Build input validator chain for element
        $this->attachValidators($input, $element);
        $this->attachFilters($input, $element->getAttribute('data-filters'));

        // TODO: Add custom validator(s) -> $dataValidator = $input->getAttribute('data-validator');

        // Can't be empty if it has a required attribute
        if ($element->hasAttribute('required')) {
            $input->getValidatorChain()->attach(new Validator\NotEmpty());
        } else {
            $input->setRequired(false);
        }

        // Validate regex patter
        if ($pattern = $element->getAttribute('pattern')) {
            $input->getValidatorChain()->attach(new Validator\Regex(sprintf('/%s/', $pattern)));
        }
    }

    abstract function attachValidators(InputInterface $input, DOMElement $element);

    public function attachFilters(InputInterface $input, $filters)
    {
        $filters = explode(',', $filters);
        foreach ($filters as $filter) {
            // TODO: Needs to fixed when zend-inputfilter 3 is released.
            if (array_key_exists($filter, $this->filters)) {
                $class = $this->filters[$filter];
                $input->getFilterChain()->attach(new $class);
            }
        }
    }
}
