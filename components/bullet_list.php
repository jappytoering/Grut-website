<?php
/**
 * Component: Bullet List (voor overlays)
 */
$title = $content['title'] ?? '';
$bullets = array_map('trim', explode(PHP_EOL, $content['bullets'] ?? ''));
?>
<?php if($title): ?>
<h4 style="margin-bottom: 0.5rem; margin-top: 2rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= htmlspecialchars($title) ?></h4>
<?php endif; ?>
<ul style="list-style: none; padding-left: 0; margin-bottom: 2rem;">
<?php foreach($bullets as $bullet): if(empty($bullet)) continue; ?>
<li style="margin-bottom: 0.5rem;"><span style="color: var(--color-green);">✓</span> <?= htmlspecialchars($bullet) ?></li>
<?php endforeach; ?>
</ul>
