<?php

namespace backend\widgets;

use Yii;
use yii\base\Widget;
use yii\base\Model;
use yii\helpers\Json;
use yii\base\InvalidConfigException;
use backend\widgets\assets\DistributionAsset;
use common\models\catalog\distribution\Parser;

/**
 * Class Distribution
 * @package backend\widgets
 */
class Distribution extends Widget
{
    public $model;
    public $productManager;

    private $_errors = [];

    public function init()
    {
        parent::init();

        if (!$this->hasDistribution()) {
            throw new InvalidConfigException("'Model' must be specified and will be valid 'Distribution' object");
        }
    }

    public function run()
    {
        $view = $this->getView();
        DistributionAsset::register($view);

        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $post = $request->post();

            if (isset($post['NS']) && is_array($post['NS'])) {
                $NS = $post['NS'];
            } else {
                $NS = [
                    'step'    => -1,
                    'id'      => $this->model->id,
                    'last_id' => 0,
                    'done'    => [
                        'upd' => 0,
                        'err' => 0,
                        'crc' => 0,
                    ],
                ];
            }

            $start_time = time();
            $parser = new Parser($this->productManager, $NS);

            if ($NS['step'] < 0) {
                $NS['step']++;
            } elseif ($NS['step'] < $NS['count']) {
                $result = $parser->parseElements($NS['step'], $start_time);
                $counter = 0;
                foreach ($result as $key => $value) {
                    $NS['done'][$key] += $value;
                    $counter += $value;
                }

                if (!$counter) {
                    $NS['step']++;
                    $NS["last_id"] = 0;
                }
            }

            if (count($this->_errors) == 0) {
                if ($NS['step'] < $NS['count']) {
                    echo '<div class="alert alert-warning small"><span class="lead">' . Yii::t('app', 'Distribution products in database...') . '</span><br><br>';
                    foreach ($NS['done'] as $name => $value) {
                        echo '<span><label>' . Yii::t('app', 'distribution-' . $name . ': {value}', ['value' => $value]) . '</span><br>';
                    }
                    echo '</div>';
                    echo '<script>DoNext(' . Json::encode(["NS" => $NS]) . ');</script>';
                } else {
                    echo '<div class="alert alert-success small"><span class="lead">' . Yii::t('app', 'Distribution completed') . '</span><br><br>';
                    foreach ($NS['done'] as $name => $value) {
                        echo '<span><label>' . Yii::t('app', 'distribution-' . $name . ': {value}', ['value' => $value]) . '</span><br>';
                    }
                    echo '</div>';
                    echo '<script>EndDistribution();</script>';
                }
            } else {
                echo '<script>EndDistribution();</script>';
            }

            Yii::$app->end();
        }

        return $this->render('distribution', [
            'profile' => $this->model,
        ]);
    }

    protected function hasDistribution()
    {
        return $this->model instanceof Model;
    }
}