<?php
/**
 * Component: Hero
 * Expects $content variable from engine.php
 */
$title = $content['title'] ?? 'Wij maken digitale<br class="desktop-br"/> producten beter';
$tags = $content['tags'] ?? [
    ['icon' => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>', 'label' => 'Digitale strategie'],
    ['icon' => '<path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>', 'label' => 'Gebruiksvriendelijkheid'],
    ['icon' => '<polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>', 'label' => 'Conversie']
];
$bg_mobile = $content['bg_mobile'] ?? '/assets/1.hero-mobile.webp?v=4';
$bg_desktop = $content['bg_desktop'] ?? '/assets/1.hero-desktop.webp?v=4';
?>
<section class="slide hero" data-nav-theme="dark" id="hero">
<div class="hero__bg">
    <picture class="hero-image-container">
        <!-- Mobiel (tot 768px breedte). -->
        <source media="(max-width: 768px)" srcset="<?= htmlspecialchars($bg_mobile) ?>" type="image/webp">
        <!-- Desktop fallback -->
        <img alt="Hero background" fetchpriority="high" loading="eager" src="<?= htmlspecialchars($bg_desktop) ?>" class="responsive-bg-img" />
    </picture>
<div class="hero__bg-placeholder" style="position:absolute; top:0;left:0;width:100%;height:100%;background: linear-gradient(180deg, rgba(39, 20, 20, 0.4) 0%, rgba(18, 15, 8, 0.4) 77%, rgba(45, 34, 15, 0.8) 100%);"></div>
</div>
<div class="hero__overlay"></div>
<div class="slide__content hero__content">
<h1 class="hero__heading reveal"><?= $title ?></h1>
<div class="hero__sub-content reveal" style="animation-delay: 0.1s;">
    <a href="#diensten" class="hero__expertise" data-cursor-text="Naar diensten" data-cursor-emoji="👇" style="cursor: none; text-decoration: none;">
        <span>Experts in</span>
        <?php foreach ($tags as $tag): ?>
        <span class="hero__expertise-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="hero__icon"><?= $tag['icon'] ?></svg>
            <?= htmlspecialchars($tag['label']) ?>
        </span>
        <?php endforeach; ?>
    </a>
</div>
</div>
<div class="hero__scroll-indicator">
<div class="scroll-line"></div>
</div>
</section>
