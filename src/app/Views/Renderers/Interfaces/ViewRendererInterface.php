<?php

namespace App\Views\Renderers\Interfaces;

interface ViewRendererInterface
{
    public function renderTree(array $tree);
    public function renderNode(object $node);
}
