<?php

namespace common\models;

/**
 * Interface AggregateRoot
 * @package common\models
 */
interface AggregateRoot
{
    public function releaseEvents();
}