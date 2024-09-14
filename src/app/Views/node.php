<li class="files__item" data-id="<?= $id ?>" data-type="<?= $type ?>">
    <?= $name ?>
    <?php if (!empty($children)): ?>
        <ul class="files__list">
            <?php foreach ($children as $child): ?>
                <?= $this->renderNode($child); ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</li>
