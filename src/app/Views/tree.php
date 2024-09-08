<ul>
    <?php foreach ($tree as $node): ?>
        <?php echo $this->renderNode($node); ?>
    <?php endforeach; ?>
</ul>
