<?php

namespace common\collects\validators;

use yii\validators\RegularExpressionValidator;

/**
 * Class Username
 * @package common\collects\validators
 */
class Username extends RegularExpressionValidator
{
    public $pattern = '#^[a-z0-9_-]*$#s';
    public $message = 'Логин должен содержать только цифры, знаки "-" и "_", латинские буквы в нижнем регистре';
}