<?php

namespace common\collects\validators;

use yii\validators\RegularExpressionValidator;

/**
 * Class Password
 * @package common\collects\validators
 */
class Password extends RegularExpressionValidator
{
    public $pattern = '#^[a-zA-Z0-9_-]*$#s';
    public $message = 'Пароль должен содержать только цифры, знаки "-" и "_", латинские буквы в нижнем регистре';
}