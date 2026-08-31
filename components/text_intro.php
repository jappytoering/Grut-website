<?php
/**
 * Component: Text Intro
 * Expects $content variable from engine.php
 */
$text = $content['text'] ?? 'Wij geloven dat elke digitale ervaring moeiteloos moet voelen';
$bg_color = $content['bg_color'] ?? 'var(--purple)';
$show_smile = $content['show_smile'] ?? true;
?>
<section class="slide" data-nav-theme="dark">
<div class="slide__bg" style="background-color: <?= htmlspecialchars($bg_color) ?>;"></div>
<div class="slide__content missie-slide reveal">
    <div class="missie__tab-content active" style="display: flex; align-items: center; justify-content: center; min-height: 50vh;">
        <h2 class="missie__text" style="text-align: center; font-size: clamp(2rem, 5vw, 4rem); line-height: 1.2;">
            <?= nl2br(htmlspecialchars($text)) ?>
        </h2>
    </div>
    <?php if ($show_smile): ?>
    <img alt="Grut smile" class="smile-image" src="/assets/grut-smile.svg?v=4"/>
    <?php endif; ?>
</div>
</section>
