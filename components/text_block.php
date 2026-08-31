<?php
/**
 * Component: Text Block (voor overlays en standaard paginas)
 */
$text = $content['text'] ?? '';
$is_intro = !empty($content['is_intro']) && $content['is_intro'] == 'true';
$title = $content['title'] ?? '';
?>
<?php if ($title): ?>
<h4 style="margin-bottom: 0.5rem; margin-top: 2rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= htmlspecialchars($title) ?></h4>
<?php endif; ?>
<?php if ($text): ?>
    <?php if ($is_intro): ?>
        <p class="overlay-intro"><?= nl2br(htmlspecialchars($text)) ?></p>
    <?php else: ?>
        <p><?= nl2br(htmlspecialchars($text)) ?></p>
    <?php endif; ?>
<?php endif; ?>
