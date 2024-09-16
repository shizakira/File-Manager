<?php

namespace Core\Traits;

trait Singleton
{
    protected static ?self $instance = null;

    public static function getInstance(): self
    {
        self::$instance ??= new static();
        return self::$instance;
    }

    public function __construct()
    {
        $this->init();
    }

    protected function init() {}

    final public function __clone() {}

    final public function __wakeup() {}
}
