<?php

namespace backend\widgets;

use yii\helpers\Html;
use yii\helpers\Json;

class ActiveField extends \kartik\form\ActiveField
{
    public $copyFrom = null;
    public $makeSlug = null;
    public $replace = [];
    public $makeKey = null;

    public function init()
    {
        $fields = [
            'makeSlug' => [
                'buttonIdSuffix' => '-slugButton',
                'buttonIcon'     => 'code-tags',
                'jsMethodName'   => 'Admin.makeSlug',
            ]
        ];

        if ($this->makeSlug) {
            $id = Html::getInputId($this->model, $this->attribute);
            $buttonId = $id . $fields['makeSlug']['buttonIdSuffix'];
            $this->addon['append'] = [
                'content'  => Html::button(
                    '<i class="mdi mdi-' . $fields['makeSlug']['buttonIcon'] . '"></i>',
                    ['class' => 'btn btn-primary', 'id' => $buttonId]
                ),
                'asButton' => true,
            ];
            $encodedFrom = Json::encode($this->makeSlug);
            $encodedTo = Json::encode('#' . $id);
            $replacement = Json::encode($this->replace);
            $js = <<<EOT
$("#$buttonId").click(function() {
    {$fields['makeSlug']['jsMethodName']}($encodedFrom, $encodedTo, $replacement);
});
EOT;
            $this->form->getView()->registerJs($js);
        }

        parent::init();
    }
}