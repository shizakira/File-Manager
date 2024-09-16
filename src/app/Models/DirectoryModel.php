<?php

namespace App\Models;

use App\Models\AbstractModel;

class DirectoryModel extends AbstractModel
{
    private $children = [];

    public function __construct($id, $name, $parentId = null)
    {
        parent::__construct($id, $name, $parentId);
    }

    public function getType()
    {
        return 'directory';
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }
}
