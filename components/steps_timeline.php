<?php
/**
 * Component: Steps Timeline
 * Expects $content variable from engine.php
 */
$title = $content['title'] ?? 'Programma';
$steps = $content['steps'] ?? [];
$bg_color = $content['bg_color'] ?? 'var(--theme-bg, #0b1120)';

if (empty($steps)) {
    $steps = [
        ['label' => 'Stap 1', 'title' => 'Verkenning', 'content' => 'Wat is de kern van je idee?']
    ];
}
?>
<section class="slide" data-nav-theme="dark">
<div class="slide__bg" style="background-color: <?= htmlspecialchars($bg_color) ?>;"></div>
<div class="slide__content prototype-sprint-container" style="padding-top:4rem; padding-bottom:6rem;">
    <h4 style="margin-bottom: 1.5rem; margin-top: 3rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= htmlspecialchars($title) ?></h4>
    <div class="prototype-days-list">
        <?php foreach ($steps as $step): ?>
            <details class="day-item">
                <summary>
                    <div class="day-label-container">
                        <div class="day-label"><?= htmlspecialchars($step['label']) ?></div>
                        <div class="day-divider"></div>
                        <div class="day-desc-title"><?= htmlspecialchars($step['title']) ?></div>
                    </div>
                    <span class="day-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                    </span>
                </summary>
                <div class="day-content">
                    <p><?= htmlspecialchars($step['content']) ?></p>
                </div>
            </details>
        <?php endforeach; ?>
    </div>
</div>
</section>
