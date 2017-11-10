<?php

namespace common\managers;

use common\dispatchers\DeferredEventDispatcher;

/**
 * Class TransactionManager
 * @package common\managers
 */
class TransactionManager
{
    private $_dispatcher;

    public function __construct(DeferredEventDispatcher $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
    }

    public function wrap(callable $function)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->_dispatcher->defer();
            $function();
            $transaction->commit();
            $this->_dispatcher->release();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->_dispatcher->clean();
            throw $e;
        }
    }
}