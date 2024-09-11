<li data-id="<?= $id ?>" data-type="<?= $type ?>">
    <?= $name ?>
    <?php if (!empty($children)): ?>
        <ul>
            <?php foreach ($children as $child): ?>
                <?= $this->renderNode($child); ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</li>
