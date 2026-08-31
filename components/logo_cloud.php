<?php
/**
 * Component: Logo Cloud
 * Expects $content variable from engine.php
 */
$title = $content['title'] ?? 'Wij werkten al voor:';
$logos = $content['logos'] ?? [];
$bg_color = $content['bg_color'] ?? 'var(--theme-bg, #0b1120)';

if (empty($logos)) {
    $logos = [
        ['image' => '/assets/sanoma_logo.svg', 'alt' => 'Sanoma'],
        ['image' => '/assets/Daklab logo licht.svg', 'alt' => 'Daklab']
    ];
}
?>
<section class="slide" data-nav-theme="dark">
<div class="slide__bg" style="background-color: <?= htmlspecialchars($bg_color) ?>;"></div>
<div class="slide__content" style="padding-top:2rem; padding-bottom:2rem; display: flex; align-items: center; justify-content: center; width: 100%; flex-wrap: wrap; gap: 2rem;">
    <?php if ($title): ?>
    <p style="font-family: var(--font-body); font-size: var(--body-size, 16px); color: rgba(255,255,255,0.6); margin: 0; white-space: nowrap;"><?= htmlspecialchars($title) ?></p>
    <?php endif; ?>
    <div class="prototype-trusted-logos" style="display: flex; align-items: center; justify-content: center; gap: 1.5rem; flex-wrap: wrap;">
        <?php foreach ($logos as $logo): ?>
        <div class="logo-box" style="padding: 1rem 1.5rem; aspect-ratio: auto; border-radius: 12px; height: auto;">
            <img src="<?= htmlspecialchars($logo['image']) ?>" alt="<?= htmlspecialchars($logo['alt'] ?? '') ?>" loading="lazy" decoding="async" style="height: 24px; width: auto; max-width: 150px; object-fit: contain;">
        </div>
        <?php endforeach; ?>
    </div>
</div>
</section>
