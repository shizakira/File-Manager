<?php

namespace App\Models;

use App\Models\AbstractModel;

class DirectoryModel extends AbstractModel
{
    private array $children = [];

    public function getType(): string
    {
        return 'directory';
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function setChildren($children): void
    {
        $this->children = $children;
    }
}
