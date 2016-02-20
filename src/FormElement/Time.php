<?php

namespace Xtreamwayz\HTMLFormValidator\FormElement;

use Xtreamwayz\HTMLFormValidator\FormElement\DateTime as DateTimeElement;

class Time extends DateTimeElement
{
    protected $format = 'H:i';
}
