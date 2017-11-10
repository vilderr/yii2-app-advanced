<?php

namespace common\collects\validators;

use yii\validators\RegularExpressionValidator;

/**
 * Class SlugValidator
 * @package common\collects\validators
 */
class SlugValidator extends RegularExpressionValidator
{
    public $pattern = '#^[a-z0-9_-]*$#s';
    public $message = 'Разрешены только следующие символы "a-z0-9_-"';
}