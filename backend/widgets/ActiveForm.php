<?php

namespace backend\widgets;

/**
 * Class ActiveForm
 * @package backend\widgets
 */
class ActiveForm extends \kartik\form\ActiveForm
{
    public function init()
    {
        if (!isset($this->fieldConfig['class'])) {
            $this->fieldConfig['class'] = ActiveField::class;
        }

        parent::init();
    }
}