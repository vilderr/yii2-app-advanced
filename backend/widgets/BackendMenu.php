<?php


namespace backend\widgets;

use Yii;
use backend\widgets\assets\BackendMenuAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

class BackendMenu extends Menu
{
    public $linkTemplate = '<a href="{url}"><i class="mdi mdi-{icon}"></i><span>{label}</span></a>';

    public function run()
    {
        BackendMenuAsset::register($this->getView());
        parent::run();
    }

    protected function renderItem($item)
    {
        $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

        return strtr($template, [
            '{url}' => isset($item['url']) ? Html::encode(Url::to($item['url'])) : '#',
            '{label}' => $item['label'],
            '{icon}'  => isset($item['icon']) ? $item['icon'] : 'angle-right',
        ]);
    }

    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if (substr($route, 0, 1) !== '/' && Yii::$app->controller) {
                $route = ltrim(Yii::$app->controller->module->getUniqueId() . '/' . $route, '/');
            }
            $route = ltrim($route,'/');
            if ($route != $this->route) {
                return false;
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
}