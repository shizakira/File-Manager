<?php

namespace App\Factories;

use App\Models\FileModel;
use App\Models\DirectoryModel;

class ModelFactory
{
    public static function createModel($row)
    {
        if ($row['type'] === 'directory') {
            return new DirectoryModel($row['id'], $row['name'], $row['parent_id']);
        }

        return new FileModel($row['id'], $row['name'], $row['parent_id']);
    }
}
