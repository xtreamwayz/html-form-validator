<?php

namespace Xtreamwayz\HTMLFormValidator;

use InvalidArgumentException;

/**
 * TODO: This is a hack to make validators work with servicemanager 3. Needs fix when zend-validator 3 is released.
 *
 * @codeCoverageIgnore
 */
class ValidatorManager
{
    /**
     * Default set of validators
     *
     * @var array
     */
    protected static $validators = [
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

    public static function getValidator($name)
    {
        if (!self::hasValidator($name)) {
            throw new InvalidArgumentException('Filter not found');
        }

        return self::$validators[$name];
    }

    public static function hasValidator($name)
    {
        return (isset(self::$validators[$name])) ? true : false;
    }
}
