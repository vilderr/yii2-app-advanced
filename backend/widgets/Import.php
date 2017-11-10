<?php

namespace backend\widgets;

use common\dispatchers\EventDispatcher;
use Yii;
use yii\base\Widget;
use yii\base\Model;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\catalog\import\ImportProfileTree as Tree;
use common\models\catalog\import\Parser;
use backend\widgets\assets\ImportAsset;

/**
 * Class Import
 * @package backend\widgets
 */
class Import extends Widget
{
    public $model;

    public $productManager;
    public $propertyManager;
    public $propertyValueManager;

    private $_errors = [];

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {
        if (!$this->hasModel()) {
            throw new InvalidConfigException("'Model' must be specified.");
        }
    }

    public function run()
    {
        $view = $this->getView();
        ImportAsset::register($view);
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (isset($post['NS']) && is_array($post['NS'])) {
                $NS = $post['NS'];
            } else {
                $NS = [
                    'step'       => 0,
                    'profile_id' => $this->model->id,
                    'url'        => $this->model->products_url,
                    'token'      => $this->model->api_key,
                    'sections'   => ArrayHelper::getColumn($this->model->sections, 'value'),
                    'last_id'    => 0,
                    'done'       => [
                        'add' => 0,
                        'upd' => 0,
                        'err' => 0,
                        'crc' => 0,
                    ],
                ];
            }

            if ($NS['step'] < 1) {
                Yii::$app->db->createCommand()->truncateTable(Tree::tableName())->execute();
                $NS['step']++;
            } elseif ($NS['step'] < 2) {
                $parser = new Parser($this->productManager, $this->propertyManager, $this->propertyValueManager, $NS);
                $result = $parser->importElements(time(), 30);

                $counter = 0;
                foreach ($result as $key => $value) {
                    $NS['done'][$key] += $value;
                    $counter += $value;
                }
                if (!$counter)
                    $NS['step']++;
            }

            if (count($this->_errors) == 0) {
                if ($NS["step"] < 2) {
                    echo '<div class="alert alert-warning small"><span class="lead">Импорт товаров из удаленной базы</span><br><br>';
                    foreach ($NS['done'] as $name => $value) {
                        echo '<span><label>' . Yii::t('app', 'import-' . $name . ': {value}', ['value' => $value]) . '</span><br>';
                    }
                    echo '</div>';
                    echo '<script>DoNext(' . Json::encode(["NS" => $NS]) . ');</script>';
                } else {
                    echo '<div class="alert alert-success small notify"><span class="lead">Импорт завершен</span><br><br>';
                    foreach ($NS['done'] as $name => $value) {
                        echo '<span><label>' . Yii::t('app', 'import-' . $name . ': {value}', ['value' => $value]) . '</span><br>';
                    }
                    echo '</div>';
                    echo '<script>EndImport();</script>';
                }
            }

            Yii::$app->end();
        }

        return $this->render('import');
    }

    /**
     * @return bool
     */
    protected function hasModel()
    {
        return $this->model instanceof Model;
    }
}