<?php

namespace App\Models;

abstract class AbstractModel
{
    protected $id;
    protected $name;
    protected $parentId;

    public function __construct($id, $name, $parentId = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parentId = $parentId;
    }

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
