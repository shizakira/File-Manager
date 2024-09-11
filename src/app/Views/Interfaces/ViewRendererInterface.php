<?php

namespace App\Views\Interfaces;

interface ViewRendererInterface
{
    public function renderTree($tree);
    public function renderNode($node);
}
