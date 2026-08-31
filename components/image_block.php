<?php
/**
 * Component: Image Block (voor overlays)
 */
$image = $content['image'] ?? '';
$alt = $content['alt'] ?? '';
if ($image):
?>
<img alt="<?= htmlspecialchars($alt) ?>" class="overlay-image" loading="lazy" src="<?= htmlspecialchars($image) ?>"/>
<?php endif; ?>
