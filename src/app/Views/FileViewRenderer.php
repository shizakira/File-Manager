<?php

namespace App\Views;

use App\Views\Interfaces\ViewRendererInterface;

class FileViewRenderer implements ViewRendererInterface
{
    public function renderTree($tree)
    {
        if (empty($tree)) {
            return '';
        }

        ob_start();
        $this->renderView('tree.php', ['tree' => $tree]);

        return ob_get_clean();
    }

    public function renderNode($node)
    {
        ob_start();
        $this->renderView('node.php', ['node' => $node]);

        return ob_get_clean();
    }

    private function renderView($view, $data = [])
    {
        extract($data);
        include $view;
    }
}
