<?php
/**
 * @var $this \yii\web\View
 * @var $content string
 */

use frontend\assets\AppAsset;
use frontend\widgets\CategoryMenu;
use common\repo\CategoryRepository;

AppAsset::register($this);
/** @var CategoryRepository $categories */
$categories = Yii::$container->get(CategoryRepository::class);
?>
<?php $this->beginContent('@frontend/views/layouts/frame.php'); ?>
<div id="wrapper">
    <div id="wrapper-start">
        <header id="main-header">
            <div class="b-navbar container">
                <div class="top wrap">
                    <div class="row justify-content-between">
                        <div class="brand col-8 col-sm-7 col-xl-5"><a href="/">
                                <img src="/static/img/logo.png" class="img-fluid"></a>
                        </div>
                        <div class="panel col-15 col-sm-16 col-xl-18">
                            <div class="d-flex justify-content-end justify-content-sm-between h-100">
                                <div class="town h-100">
                                    <div class="bar d-flex align-items-center">
                                        <i></i><span class="title">Ваш город:</span> <a href="#" class="link">Москва</a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center h-100">
                                    <div class="wishlist h-100">
                                        <div class="bar d-flex align-items-center">
                                            <i></i><a href="#" class="link">Мне нравится</a>
                                        </div>
                                    </div>
                                    <div class="compare h-100">
                                        <div class="bar d-flex align-items-center">
                                            <i></i><a href="#" class="link">Список сравнения</a>
                                        </div>
                                    </div>
                                    <div class="user h-100">
                                        <div class="bar d-flex align-items-center">
                                            <i></i><a href="#" class="link">Личный кабинет</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bottom wrap">
                    <div class="row justify-content-between">
                        <div class="navbar-outer col-3 col-sm-7 col-xl-5">
                            <div class="navbar-inner">
                                <div id="nav-toggle-wrap">
                                    <a href="#" class="toggle-menu bar"><span class="icon"><span></span></span> <label>Каталог <span>товаров</span></label></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-20 col-sm-16 col-xl-18">
                            <div id="search-bar">
                                <div class="input-group input-group-lg resizeble">
                                    <input type="text" class="form-control search--input"
                                           placeholder="<?= Yii::t('app', 'Search products'); ?>"
                                           aria-label="<?= Yii::t('app', 'Search products'); ?>">
                                    <div class="input-group-btn search--toggle">
                                        <button type="button" class="btn btn-white dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                            <span>Категория товаров</span> <i class="fa fa-angle-down caret"
                                                                              aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something</a>
                                        </div>
                                    </div>
                                    <span class="input-group-btn"><button class="btn btn-light search--btn"
                                                                          type="button" ><i class="mdi mdi-magnify"
                                                                                           style="line-height: 1"></i><span><?= Yii::t('app', 'Go Search')?></span></button></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="main-menu-wrapper">
                <div class="container outer">
                        <div class="row">
                            <div class="col-24 menu-header">
                                <div class="close-menu"><span class="mdi mdi-chevron-left"></span></div>
                            </div>
                            <div class="col-9 col-sm-8 col-xl-6 menu-wrap">
                                    <?= CategoryMenu::widget([
                                        'options'         => [
                                            'class' => 'top-category-menu',
                                        ],
                                        'items'           => $categories->getList(3, false, true),
                                        'route' => \yii\helpers\Url::current(),
                                        'submenuTemplate' => "\n<div class='subtree'><ul>\n{items}\n</ul></div>\n",
                                        'activateParents' => true
                                    ]); ?>
                                </div>
                        </div>
                </div>
            </div>
            <div class="bg"></div>
        </header>
        <section id="content">
            <?= $content; ?>
        </section>
    </div>
    <div id="wrapper-end">
        <footer>
            <section class="pt-5 pb-5 about--bg">
                <div class="container">
                    <div class="section about-list mb-0">
                        <h3 class="title slider-title">О проекте</h3>
                        <div class="row">
                            <div class="col-md-15 mb-4 mb-md-0">
                                <p>Выбрав товары на «Мегамол», вы можете сразу оформить на них заказ. Вам не потребуется переходить на сайты магазинов и регистрироваться на каждом из них. Добавляйте товары из разных магазинов в единую корзину и оформляйте заказы в один прием.</p>
                                <p>Для тех товаров, которые можно заказать прямо на «Мегамол», отображается кнопка В корзину. Вы можете отобрать такие товары, воспользовавшись фильтром Заказать на Маркете на странице Цены карточки модели или в результатах поиска.</p>
                                <div class="btn-group-lg">
                                    <a href="#" class="btn btn-white">О проекте</a>
                                    <a href="#" class="btn btn-white">Сотрудничество</a>
                                </div>
                            </div>
                            <div class="col-md-8 ml-md-auto">
                                <p class="lead">Для каждого вида доставки указана предварительная стоимость. Она может измениться после того, как вы укажете адрес доставки.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="pt-5 pb-5">
                <div class="container">
                    <div class="text-center pt-5">
                        <p class="mb-4"><img src="/static/img/foot__logo.png" class="img-fluid"/></p>
                        <p class="text-dark">Copyright © 1995-2017 «Bigmall». Все права защищены.</p>
                        <div class="d-flex flex-column flex-md-row  justify-content-center">
                            <div class="pl-md-2 pr-md-2">
                                <a href="#" class="text-dark">Пользовательское соглашение</a>
                            </div>
                            <div class="pl-md-2 pr-md-2">
                                <a href="#" class="text-dark">Защита конфиденциальности</a>
                            </div>
                            <div class="pl-md-2 pr-md-2">
                                <a href="#" class="text-dark">Файлы cookie и AdChoice</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </footer>
    </div>
</div>
<div id="preloader-cover">
    <div class="spinner-ring"></div>
</div>
<?php $this->endContent(); ?>
