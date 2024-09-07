<?php

namespace App\Repositories;

interface FileRepositoryInterface
{
    public function getFiles();
    public function addDirectory($name, $parent_id);
    public function uploadFile($name, $parent_id);
    public function deleteItem($id);
    public function buildTree($parent_id = null);
}
