<?php

$array1 = [
    'invalid_isempty' => [
        'isEmpty' => '',
    ],

    'invalid' => [
        'emailAddressInvalidHostname' => "'example' is not a valid hostname for the email address",
        'hostnameInvalidHostname'     => "The input does not match the expected structure for a DNS hostname",
        'hostnameLocalNameNotAllowed' => "The input appears to be a local network name but local network names are not allowed",
    ],

    'invalid_multiple' => [
        [
            'emailAddressInvalidHostname' => "'example' is not a valid hostname for the email address",
            'hostnameInvalidHostname'     => "The input does not match the expected structure for a DNS hostname",
            'hostnameLocalNameNotAllowed' => "The input appears to be a local network name but local network names are not allowed",
        ],
    ],

    'invalid_multiple_format' => [
        [
            'emailAddressDotAtom'          => "'john.doe@example.com|jane.doe' can not be matched against dot-atom format",
            'emailAddressQuotedString'     => "'john.doe@example.com|jane.doe' can not be matched against quoted-string format",
            'emailAddressInvalidLocalPart' => "'john.doe@example.com|jane.doe' is not a valid local part for the email address",
        ],
    ],
];

$array2 = [
    'invalid_isempty' => [
        'isEmpty' => 'Value is required and can\'t be empty',
    ],

    'invalid' => [
        'emailAddressInvalidHostname' => '',
        'hostnameInvalidHostname'     => '',
        'hostnameLocalNameNotAllowed' => '',
        'extra'                       => '',
    ],

    'invalid_multiple' => [
        [
            'emailAddressInvalidHostname' => '',
            'hostnameInvalidHostname'     => '',
            'hostnameLocalNameNotAllowed' => '',
            'test'
        ],
    ],

    'invalid_multiple_format' => [
        [
            'emailAddressDotAtomm'          => '',
            'emailAddressQuotedString'     => '',
            'emailAddressInvalidLocalPart' => '',
        ],
    ],
];

$result = array_diff_key_multi($array1, $array2);
var_dump($result);

var_dump(array_diff_key($array1, $array2));

function array_diff_key_multi($array1, $array2)
{
    $result = [];

    if ($res = array_merge(array_diff_key($array1, $array2), array_diff_key($array2, $array1))) {
        echo __LINE__ . PHP_EOL;
        $result = $res;
    }

    foreach ($array1 as $key => $val) {
        if (is_array($val) && isset($array2[$key])) {
            echo __LINE__ . ":$key" . PHP_EOL;
            if ($res = array_merge(array_diff_key($val, $array2[$key]), array_diff_key($array2[$key], $val))) {
                echo __LINE__ . ":$key" . PHP_EOL;
                $result[$key] = $res;
            } elseif ($res = array_diff_key_multi($val, $array2[$key])) {
                echo __LINE__ . ":$key" . PHP_EOL;
                $result[$key] = $res;
            }
        } elseif (!isset($array2[$key])) {
            echo __LINE__ . ":$key" . PHP_EOL;
            $result[$key] = $val;
        }
    }

    return $result;
}
