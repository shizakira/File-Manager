<?php

namespace App\Factories;

use App\Models\AbstractModel;
use App\Models\FileModel;
use App\Models\DirectoryModel;

class ModelFactory
{
    public static function createModel(array $row): AbstractModel
    {
        $map = [
            'file' => FileModel::class,
            'directory' => DirectoryModel::class
        ];

        if (!isset($row['type']) || !class_exists($map[$row['type']])) {
            throw new \InvalidArgumentException('Missing parameters for creating model.');
        }

        $class = $map[$row['type']];

        return new $class($row['id'], $row['name'], $row['parent_id']);
    }
}
