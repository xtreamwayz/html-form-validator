<?php

namespace Xtreamwayz\HTMLFormValidator;

use InvalidArgumentException;

/**
 * TODO: This is a hack to get the input filter with servicemanager 3 working.
 * TODO: Needs fix when zend-inputfilter 3 is released.
 */
class FilterManager
{
    /**
     * Default set of filters
     *
     * @var array
     */
    protected static $filters = [
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

    public static function getFilter($name)
    {
        if (!self::hasFilter($name)) {
            throw new InvalidArgumentException('Filter not found');
        }

        return self::$filters[$name];
    }

    public static function hasFilter($name)
    {
        return (isset(self::$filters[$name])) ? true : false;
    }
}
