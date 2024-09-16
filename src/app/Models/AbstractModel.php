<?php

namespace App\Models;

abstract class AbstractModel
{
    public function __construct(
        protected $id,
        protected $name,
        protected $parentId = null
    ) {}

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    abstract public function getType();
}
