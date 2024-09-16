<?php

namespace App\Models;

use App\Models\AbstractModel;

class FileModel extends AbstractModel
{
    public function getType()
    {
        return 'file';
    }
}
