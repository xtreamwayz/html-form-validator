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
     * TODO: This is a hack to get the input filter with servicemanager 3 working.
     * TODO: Needs fix when zend-inputfilter 3 is released.
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

    /**
     * Default set of validators
     *
     * TODO: This is a hack to get the input filter with servicemanager 3 working.
     * TODO: Needs fix when zend-inputfilter 3 is released.
     *
     * @var array
     */
    protected $validators = [
        'alnum'                    => 'Zend\I18n\Validator\Alnum',
        'alpha'                    => 'Zend\I18n\Validator\Alpha',
        'barcodecode25interleaved' => 'Zend\Validator\Barcode\Code25interleaved',
        'barcodecode25'            => 'Zend\Validator\Barcode\Code25',
        'barcodecode39ext'         => 'Zend\Validator\Barcode\Code39ext',
        'barcodecode39'            => 'Zend\Validator\Barcode\Code39',
        'barcodecode93ext'         => 'Zend\Validator\Barcode\Code93ext',
        'barcodecode93'            => 'Zend\Validator\Barcode\Code93',
        'barcodeean12'             => 'Zend\Validator\Barcode\Ean12',
        'barcodeean13'             => 'Zend\Validator\Barcode\Ean13',
        'barcodeean14'             => 'Zend\Validator\Barcode\Ean14',
        'barcodeean18'             => 'Zend\Validator\Barcode\Ean18',
        'barcodeean2'              => 'Zend\Validator\Barcode\Ean2',
        'barcodeean5'              => 'Zend\Validator\Barcode\Ean5',
        'barcodeean8'              => 'Zend\Validator\Barcode\Ean8',
        'barcodegtin12'            => 'Zend\Validator\Barcode\Gtin12',
        'barcodegtin13'            => 'Zend\Validator\Barcode\Gtin13',
        'barcodegtin14'            => 'Zend\Validator\Barcode\Gtin14',
        'barcodeidentcode'         => 'Zend\Validator\Barcode\Identcode',
        'barcodeintelligentmail'   => 'Zend\Validator\Barcode\Intelligentmail',
        'barcodeissn'              => 'Zend\Validator\Barcode\Issn',
        'barcodeitf14'             => 'Zend\Validator\Barcode\Itf14',
        'barcodeleitcode'          => 'Zend\Validator\Barcode\Leitcode',
        'barcodeplanet'            => 'Zend\Validator\Barcode\Planet',
        'barcodepostnet'           => 'Zend\Validator\Barcode\Postnet',
        'barcoderoyalmail'         => 'Zend\Validator\Barcode\Royalmail',
        'barcodesscc'              => 'Zend\Validator\Barcode\Sscc',
        'barcodeupca'              => 'Zend\Validator\Barcode\Upca',
        'barcodeupce'              => 'Zend\Validator\Barcode\Upce',
        'barcode'                  => 'Zend\Validator\Barcode',
        'between'                  => 'Zend\Validator\Between',
        'bitwise'                  => 'Zend\Validator\Bitwise',
        'callback'                 => 'Zend\Validator\Callback',
        'creditcard'               => 'Zend\Validator\CreditCard',
        'csrf'                     => 'Zend\Validator\Csrf',
        'date'                     => 'Zend\Validator\Date',
        'datestep'                 => 'Zend\Validator\DateStep',
        'datetime'                 => 'Zend\I18n\Validator\DateTime',
        'dbnorecordexists'         => 'Zend\Validator\Db\NoRecordExists',
        'dbrecordexists'           => 'Zend\Validator\Db\RecordExists',
        'digits'                   => 'Zend\Validator\Digits',
        'emailaddress'             => 'Zend\Validator\EmailAddress',
        'explode'                  => 'Zend\Validator\Explode',
        'filecount'                => 'Zend\Validator\File\Count',
        'filecrc32'                => 'Zend\Validator\File\Crc32',
        'fileexcludeextension'     => 'Zend\Validator\File\ExcludeExtension',
        'fileexcludemimetype'      => 'Zend\Validator\File\ExcludeMimeType',
        'fileexists'               => 'Zend\Validator\File\Exists',
        'fileextension'            => 'Zend\Validator\File\Extension',
        'filefilessize'            => 'Zend\Validator\File\FilesSize',
        'filehash'                 => 'Zend\Validator\File\Hash',
        'fileimagesize'            => 'Zend\Validator\File\ImageSize',
        'fileiscompressed'         => 'Zend\Validator\File\IsCompressed',
        'fileisimage'              => 'Zend\Validator\File\IsImage',
        'filemd5'                  => 'Zend\Validator\File\Md5',
        'filemimetype'             => 'Zend\Validator\File\MimeType',
        'filenotexists'            => 'Zend\Validator\File\NotExists',
        'filesha1'                 => 'Zend\Validator\File\Sha1',
        'filesize'                 => 'Zend\Validator\File\Size',
        'fileupload'               => 'Zend\Validator\File\Upload',
        'fileuploadfile'           => 'Zend\Validator\File\UploadFile',
        'filewordcount'            => 'Zend\Validator\File\WordCount',
        'float'                    => 'Zend\I18n\Validator\IsFloat',
        'greaterthan'              => 'Zend\Validator\GreaterThan',
        'hex'                      => 'Zend\Validator\Hex',
        'hostname'                 => 'Zend\Validator\Hostname',
        'iban'                     => 'Zend\Validator\Iban',
        'identical'                => 'Zend\Validator\Identical',
        'inarray'                  => 'Zend\Validator\InArray',
        'int'                      => 'Zend\I18n\Validator\IsInt',
        'ip'                       => 'Zend\Validator\Ip',
        'isbn'                     => 'Zend\Validator\Isbn',
        'isfloat'                  => 'Zend\I18n\Validator\IsFloat',
        'isinstanceof'             => 'Zend\Validator\IsInstanceOf',
        'isint'                    => 'Zend\I18n\Validator\IsInt',
        'lessthan'                 => 'Zend\Validator\LessThan',
        'notempty'                 => 'Zend\Validator\NotEmpty',
        'phonenumber'              => 'Zend\I18n\Validator\PhoneNumber',
        'postcode'                 => 'Zend\I18n\Validator\PostCode',
        'regex'                    => 'Zend\Validator\Regex',
        'sitemapchangefreq'        => 'Zend\Validator\Sitemap\Changefreq',
        'sitemaplastmod'           => 'Zend\Validator\Sitemap\Lastmod',
        'sitemaploc'               => 'Zend\Validator\Sitemap\Loc',
        'sitemappriority'          => 'Zend\Validator\Sitemap\Priority',
        'stringlength'             => 'Zend\Validator\StringLength',
        'step'                     => 'Zend\Validator\Step',
        'timezone'                 => 'Zend\Validator\Timezone',
        'uri'                      => 'Zend\Validator\Uri',
        'recaptcha'                => 'Xtreamwayz\HTMLFormValidator\Validator\RecaptchaValidator',
    ];

    /**
     * Process element and attach validators and filters
     *
     * @param DOMElement     $element
     * @param InputInterface $input
     */
    public function __invoke(DOMElement $element, InputInterface $input)
    {
        // Build input validator chain for element
        $this->attachDefaultValidators($input, $element);
        $this->attachValidators($input, $element);
        $this->attachFilters($input, $element);

        // Enforce required and allow empty properties
        if ($element->hasAttribute('required') || $element->getAttribute('aria-required') == 'true') {
            $input->setRequired(true);
            $input->setAllowEmpty(false);
            // Attach NotEmpty validator manually so it won't use the plugin manager, which fails for servicemanager 3
            $input->getValidatorChain()->attach(new Validator\NotEmpty());
        } else {
            // Enforce properties so it doesn't try to load NotEmpty, which fails for servicemanager 3
            $input->setRequired(false);
            $input->setAllowEmpty(true);
        }

        // Validate regex pattern
        if ($pattern = $element->getAttribute('pattern')) {
            $input->getValidatorChain()->attach(new Validator\Regex(sprintf('/%s/', $pattern)));
        }

        // Always remove element data attributes in case there is sensitive data passed as an option
        $element->removeAttribute('data-validators');
        $element->removeAttribute('data-filters');
    }

    /**
     * Attach default validators for specific form element
     *
     * @param InputInterface $input
     * @param DOMElement     $element
     *
     * @return void
     */
    abstract protected function attachDefaultValidators(InputInterface $input, DOMElement $element);

    /**
     * Attach validators from data-validators attribute
     *
     * @param InputInterface $input
     * @param DOMElement     $element
     */
    protected function attachValidators(InputInterface $input, DOMElement $element)
    {
        $dataValidators = $element->getAttribute('data-validators');
        if (!$dataValidators) {
            return;
        }

        $validators = $this->parseDataAttribute($dataValidators);
        foreach ($validators as $validator => $options) {
            if (array_key_exists($validator, $this->validators)) {
                $class = $this->validators[$validator];
                $input->getValidatorChain()->attach(new $class($options));
            }
        }
    }

    /**
     * Attach filters from data-filters attribute
     *
     * @param InputInterface $input
     * @param DOMElement     $element
     */
    protected function attachFilters(InputInterface $input, DOMElement $element)
    {
        $dataFilters = $element->getAttribute('data-filters');
        if (!$dataFilters) {
            return;
        }

        $filters = $this->parseDataAttribute($dataFilters);
        foreach ($filters as $filter => $options) {
            // TODO: Needs to fixed when zend-inputfilter 3 is released.
            if (array_key_exists($filter, $this->filters)) {
                $class = $this->filters[$filter];
                $input->getFilterChain()->attach(new $class($options));
            }
        }
    }

    /**
     * Parse data attribute values for validators, filters and options
     *
     * @param $value
     *
     * @return array
     */
    protected function parseDataAttribute($value)
    {
        preg_match_all("/([a-zA-Z]+)([^|]*)/", $value, $matches, PREG_SET_ORDER);

        $validators = [];
        foreach ($matches as $match) {
            $validator = $match[1];
            $options = [];

            if ($match[2]) {
                $allOptions = explode(',', $match[2]);
                foreach ($allOptions as $option) {
                    $option = explode(':', $option);
                    $options[trim($option[0], ' {}\'\"')] = trim($option[1], ' {}\'\"');
                }
            }

            $validators[$validator] = $options;
        }

        return $validators;
    }
}
