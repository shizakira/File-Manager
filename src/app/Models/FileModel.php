<?php

namespace App\Models;

use App\Models\AbstractModel;

class FileModel extends AbstractModel
{
    public function __construct($id, $name, $parentId = null)
    {
        parent::__construct($id, $name, $parentId);
    }

    public function getType()
    {
        return 'file';
    }
}
