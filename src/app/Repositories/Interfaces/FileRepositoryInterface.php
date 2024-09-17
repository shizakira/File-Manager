<?php

namespace App\Repositories\Interfaces;

interface FileRepositoryInterface
{
    public function getFiles();
    public function getItemById(int $id);
    public function addDirectory(string $name, ?int $parentId);
    public function uploadFile(string $name, ?int $parentId);
    public function deleteItem(int $id);
}
