<?php
$id = htmlspecialchars($node['id'], ENT_QUOTES);
$type = htmlspecialchars($node['type'], ENT_QUOTES);
$name = htmlspecialchars($node['name'], ENT_QUOTES);
?>

<li data-id="<?= $id ?>" data-type="<?= $type ?>">
    <?= $name ?>
    <?php if (!empty($node['children'])): ?>
        <?php echo $this->renderTree($node['children']); ?>
    <?php endif; ?>
</li>
