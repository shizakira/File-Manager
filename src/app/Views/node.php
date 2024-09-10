<?php
$id = htmlspecialchars($node->getId(), ENT_QUOTES);
$type = htmlspecialchars($node->getType(), ENT_QUOTES);
$name = htmlspecialchars($node->getName(), ENT_QUOTES);
?>

<li data-id="<?= $id ?>" data-type="<?= $type ?>">
    <?= $name ?>
    <?php if (method_exists($node, 'getChildren') && !empty($node->getChildren())): ?>
        <ul>
            <?php foreach ($node->getChildren() as $child): ?>
                <?php echo $this->renderNode($child); ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</li>
