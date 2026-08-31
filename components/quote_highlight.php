<?php
/**
 * Component: Quote / Highlight
 * Expects $content variable from engine.php
 */
$quote = $content['quote'] ?? 'Dit is een opvallende uitspraak of USP.';
$author = $content['author'] ?? '';
$bg_color = $content['bg_color'] ?? 'var(--yellow)';
$text_color = $content['text_color'] ?? '#000000';
?>
<section class="slide" data-nav-theme="<?= ($bg_color == 'var(--yellow)' || $bg_color == '#F3C033') ? 'light' : 'dark' ?>">
<div class="slide__bg" style="background-color: <?= htmlspecialchars($bg_color) ?>;"></div>
<div class="slide__content" style="display: flex; flex-direction: column; justify-content: center; min-height: 50vh; text-align: center; padding: 4rem 2rem;">
    <h2 style="font-size: clamp(2rem, 4vw, 3.5rem); line-height: 1.2; color: <?= htmlspecialchars($text_color) ?>; max-width: 800px; margin: 0 auto; font-family: var(--font-heading);">
        "<?= nl2br(htmlspecialchars($quote)) ?>"
    </h2>
    <?php if ($author): ?>
    <p style="margin-top: 2rem; font-size: 1.2rem; font-weight: 600; color: <?= htmlspecialchars($text_color) ?>; opacity: 0.8;">
        &mdash; <?= htmlspecialchars($author) ?>
    </p>
    <?php endif; ?>
</div>
</section>
