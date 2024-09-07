<?php

namespace App\Views;

class FileViewRenderer
{
    public function renderTree($tree)
    {
        if (empty($tree)) {
            return '';
        }

        $output = '<ul>';
        foreach ($tree as $node) {
            $output .= $this->renderNode($node);
        }
        $output .= '</ul>';

        return $output;
    }

    private function renderNode($node)
    {
        $id = htmlspecialchars($node['id'], ENT_QUOTES);
        $type = htmlspecialchars($node['type'], ENT_QUOTES);
        $name = htmlspecialchars($node['name'], ENT_QUOTES);

        $output = "<li data-id=\"{$id}\" data-type=\"{$type}\">{$name}";

        if (!empty($node['children'])) {
            $output .= $this->renderTree($node['children']);
        }

        $output .= '</li>';

        return $output;
    }
}
