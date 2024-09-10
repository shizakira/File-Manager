<ul>
    <?php foreach ($tree as $node): ?>
        <?= $this->renderNode($node); ?>
    <?php endforeach; ?>
</ul>
