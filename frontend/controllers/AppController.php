<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\repo\ProductRepository as Products;
use frontend\repo\catalog\CategoryRepository as Categories;
use common\repo\content\MainSliderRepository as Slides;

/**
 * Site controller
 */
class AppController extends Controller
{
    private $_products;
    private $_categories;
    private $_slides;

    public function __construct($id, $module, Products $products, Categories $categories, Slides $slides, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->_products = $products;
        $this->_categories = $categories;
        $this->_slides = $slides;
    }

    public function actionIndex()
    {
        $this->layout = 'mainpage';
        $slides = $this->_slides->getList();
        $categories = $this->_categories->getList(1);
        $hotProducts = $this->_products->getHotList(18);
        $topViewedProducts = $this->_products->getViewedTopList(18);

        return $this->render('index', [
            'hotProducts' => $hotProducts,
            'topViewedProducts' => $topViewedProducts,
            'categories' => $categories,
            'slides' => $slides,
        ]);
    }

    public function actionOffline()
    {
        $this->layout = 'offline';

        return $this->render('offline');
    }

    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}
