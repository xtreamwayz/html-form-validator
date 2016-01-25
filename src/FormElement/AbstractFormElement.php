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
    ];

    public function __invoke(DOMElement $element, InputInterface $input)
    {
        // Build input validator chain for element
        $this->attachValidators($input, $element);
        $this->attachValidatorsFromDataAttribute($input, $element);
        $this->attachFilters($input, $element->getAttribute('data-filters'));

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

        // Cleanup html
        $element->removeAttribute('data-validators');
        $element->removeAttribute('data-filters');
    }

    abstract function attachValidators(InputInterface $input, DOMElement $element);

    public function attachValidatorsFromDataAttribute(InputInterface $input, DOMElement $element)
    {
        $dataValidators = $element->getAttribute('data-validators');
        if (!$dataValidators) {
            return;
        }

        $validators = json_decode(str_replace('\'', '"', $dataValidators), true);
        foreach ($validators as $validator => $options) {
            if (array_key_exists($validator, $this->validators)) {
                $class = $this->validators[$validator];
                $input->getValidatorChain()->attach(new $class($options[0]));
            }
        }
    }

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
