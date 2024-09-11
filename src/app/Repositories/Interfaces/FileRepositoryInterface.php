<?php

namespace App\Repositories\Interfaces;

interface FileRepositoryInterface
{
    public function getFiles();
    public function getItemById($id);
    public function addDirectory($name, $parentId);
    public function uploadFile($name, $parentId);
    public function deleteItem($id);
}
