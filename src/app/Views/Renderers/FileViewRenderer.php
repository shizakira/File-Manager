<?php

namespace App\Views\Renderers;

use App\Views\Renderers\Interfaces\ViewRendererInterface;

class FileViewRenderer implements ViewRendererInterface
{
    private const TREE = '/app/Views/tree.php';
    private const NODE = '/app/Views/node.php';

    public function renderTree($tree)
    {
        if (empty($tree)) {
            return '';
        }

        ob_start();
        $this->renderView($_SERVER['DOCUMENT_ROOT'] . self::TREE, ['tree' => $tree]);

        return ob_get_clean();
    }

    public function renderNode($node)
    {
        $data = [
            'id' => htmlspecialchars($node->getId(), ENT_QUOTES),
            'type' => htmlspecialchars($node->getType(), ENT_QUOTES),
            'name' => htmlspecialchars($node->getName(), ENT_QUOTES),
            'children' => method_exists($node, 'getChildren') ? $node->getChildren() : [],
        ];

        ob_start();
        $this->renderView($_SERVER['DOCUMENT_ROOT'] . self::NODE, $data);

        return ob_get_clean();
    }

    private function renderView($view, $data = [])
    {
        extract($data);
        include $view;
    }
}
