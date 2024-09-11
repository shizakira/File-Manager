<?php

namespace App\Views\Renderers\Interfaces;

interface ViewRendererInterface
{
    public function renderTree($tree);
    public function renderNode($node);
}
