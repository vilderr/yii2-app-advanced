<?php


namespace common;

use common\listeners\catalog\product\ProductViewedListener;
use common\models\events\EntityViewed;
use Yii;
use yii\base\BootstrapInterface;
use yii\rbac\ManagerInterface;
use yii\di\Instance;
use yii\di\Container;
use yii\queue\Queue;
use yii\caching\Cache;
use yii\mail\MailerInterface;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use common\dispatchers\EventDispatcher;
use common\dispatchers\DeferredEventDispatcher;
use common\dispatchers\AsyncEventDispatcher;
use common\dispatchers\SimpleEventDispatcher;
use common\models\events\EntityPersisted;
use common\models\events\EntityRemoved;
use common\jobs\AsyncEventJobHandler;
use common\models\catalog\product\events\PictureAssigned;
use common\listeners\catalog\product\SearchPersistListener;
use common\listeners\catalog\product\SearchRemoveListener;
use common\listeners\catalog\product\PictureDownloadListener;
use common\listeners\catalog\property\PropertyPersistListener;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(Client::class, function () use ($app) {
            return ClientBuilder::create()->build();
        });

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(Queue::class, function () use ($app) {
            return $app->get('queue');
        });

        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });

        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });

        $container->setSingleton(EventDispatcher::class, DeferredEventDispatcher::class);

        $container->setSingleton(DeferredEventDispatcher::class, function (Container $container) {
            return new DeferredEventDispatcher(new AsyncEventDispatcher($container->get(Queue::class)));
        });

        $container->setSingleton(SimpleEventDispatcher::class, function (Container $container) {
            return new SimpleEventDispatcher($container, [
                EntityPersisted::class => [
                    SearchPersistListener::class,
                    PropertyPersistListener::class,
                ],
                EntityRemoved::class   => [
                    SearchRemoveListener::class,
                ],
                PictureAssigned::class => [
                    PictureDownloadListener::class,
                ],
                EntityViewed::class    => [
                    ProductViewedListener::class,
                ],
            ]);
        });

        $container->setSingleton(AsyncEventJobHandler::class, [], [
            Instance::of(SimpleEventDispatcher::class),
        ]);
    }
}