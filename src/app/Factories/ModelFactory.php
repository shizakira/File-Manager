<?php

namespace App\Factories;

use App\Models\FileModel;
use App\Models\DirectoryModel;

class ModelFactory
{
    public static function createModel($row)
    {
        $map = [
            'file' => FileModel::class,
            'directory' => DirectoryModel::class
        ];

        $class = $map[$row['type']] ?? FileModel::class;
        return new $class($row['id'], $row['name'], $row['parent_id']);
    }
}
