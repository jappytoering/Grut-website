<?php
/**
 * Component: Card Grid
 * Expects $content variable from engine.php
 */
$title = $content['title'] ?? 'Diensten';
$cards = $content['cards'] ?? [];
$bg_color = $content['bg_color'] ?? 'var(--purple)';

// If no cards, show placeholder
if (empty($cards)) {
    $cards = [
        [
            'title' => 'Voorbeeld Kaart',
            'description' => 'Dit is een voorbeeld van een kaart.',
            'image' => 'assets/content-afbeelding.webp',
            'tags' => 'Tag 1, Tag 2',
            'theme' => 'bg-yellow',
            'modal_title' => 'Voorbeeld Modal',
            'modal_headline' => 'Meer informatie',
            'modal_content' => '<p>Dit is de inhoud van de modal. Voeg items toe via het CMS.</p>'
        ]
    ];
}
?>
<section class="slide" data-nav-theme="dark">
<div class="slide__bg" style="background-color: <?= htmlspecialchars($bg_color) ?>;"></div>
<div class="slide__content slide--with-cards">
<div class="slide__header">
<h3 class="slide__heading"><?= htmlspecialchars($title) ?></h3>
</div>
<div class="cards-grid-container">
<div class="cards-scroll">
<?php foreach ($cards as $card): 
    $themeClass = $card['theme'] ?? 'bg-yellow';
    $overlayTheme = str_replace('bg-', 'theme-', $themeClass);
    $tags = array_map('trim', explode(',', $card['tags'] ?? ''));
?>
<div class="card card--service <?= htmlspecialchars($themeClass) ?> is-interactive" data-modal-cta="Vertel meer" data-modal-title="<?= htmlspecialchars($card['modal_title'] ?? $card['title']) ?>" data-modal-headline="<?= htmlspecialchars($card['modal_headline'] ?? $card['title']) ?>" data-overlay-theme="<?= htmlspecialchars($overlayTheme) ?>">
<div class="card__image-wrapper">
<img alt="<?= htmlspecialchars($card['title']) ?>" class="card__image" loading="lazy" src="<?= htmlspecialchars($card['image'] ?? '') ?>"/>
</div>
<div class="card__content" style="padding:0; margin-top:0;">
<h4 class="card__title"><?= htmlspecialchars($card['title']) ?></h4>
<p class="card__description"><?= htmlspecialchars($card['description']) ?></p>
</div>
<div class="card__footer-row" style="justify-content: space-between; align-items: center; width: 100%;">
<div class="card__tags-wrapper">
<?php foreach ($tags as $tag): if(empty($tag)) continue; ?>
<span class="card__tag-pill"><?= htmlspecialchars($tag) ?></span>
<?php endforeach; ?>
</div>
<button aria-label="Vertel meer" class="card__plus-btn card__plus-btn--light">
<svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewbox="0 0 24 24" width="24">
<path d="M12 5v14M5 12h14"></path>
</svg>
</button>
</div>
<template class="card-overlay-content">
<?php if (!empty($card['overlay_page_id'])): ?>
    <?= render_page_blocks($card['overlay_page_id']) ?>
<?php else: ?>
    <?= $card['modal_content'] ?? '' ?>
<?php endif; ?>
</template>
</div>
<?php endforeach; ?>
</div>
</div>
</div>
</section>
