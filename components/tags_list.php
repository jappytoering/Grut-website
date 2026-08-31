<?php
/**
 * Component: Tags List (voor overlays)
 */
$tags = array_map('trim', explode(',', $content['tags'] ?? ''));
?>
<div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 2rem;">
<?php foreach ($tags as $tag): if(empty($tag)) continue; ?>
<span class="overlay__tag-pill"><?= htmlspecialchars($tag) ?></span>
<?php endforeach; ?>
</div>
