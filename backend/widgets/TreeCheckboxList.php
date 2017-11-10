<?php

namespace backend\widgets;

use yii\helpers\Html;
use yii\base\Widget;
use yii\base\Model;
use yii\base\InvalidConfigException;

/**
 * Class TreeCheckboxList
 * @package backend\widgets
 */
class TreeCheckboxList extends Widget
{
    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    public $attribute;

    public $values;

    public function init()
    {
        if (!$this->hasModel()) {
            throw new InvalidConfigException("'Model' must be specified.");
        }

        if (!is_array($this->values)) {
            throw new InvalidConfigException("'Values' must be valid array.");
        }
    }

    /**
     * @return bool
     */
    protected function hasModel()
    {
        return $this->model instanceof Model;
    }

    public function run()
    {
        echo Html::tag('div', $this->renderItems(), ['class' => 'admin-unstyled']);
    }

    protected function renderItems()
    {
        $topDepth = 0;
        $currentDepth = -1;
        $list = '';

        foreach ($this->values as $value) {
            if ($currentDepth < $value['depth']) {
                $list .= '<ul>';
            } elseif ($currentDepth == $value["depth"]) {
                $list .= '</li>';
            } else {
                while ($currentDepth > $value["depth"]) {
                    $list .= '</li>';
                    $list .= '</ul>';
                    $currentDepth--;
                }
                $list .= '</li>';
            }

            $checked = (in_array($value['id'], $this->model->values)) ? ' checked' : '';

            $list .= '<li><label><input type="checkbox" name="' . $this->model->formName() . '[' . $this->attribute . '][]" value="' . $value['id'] . '"' . $checked . '> ' . $value['name'] . '</label>';

            $currentDepth = $value['depth'];
        }

        return $list;
    }
}