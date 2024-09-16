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

        if (!isset($row['type']) || !class_exists($map[$row['type']])) {
            throw new \InvalidArgumentException('Missing parameters for creating model.');
        }

        $class = $map[$row['type']] ?? FileModel::class;
        return new $class($row['id'], $row['name'], $row['parent_id']);
    }
}
