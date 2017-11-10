<?php

namespace common\collects\validators;

use yii\validators\RegularExpressionValidator;

/**
 * Class Phone
 * @package common\collects\validators
 */
class Phone extends RegularExpressionValidator
{
    public $pattern = '/^\+[0-9]{1}\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/';
    public $message = 'Введите корректный номер телефона';
}