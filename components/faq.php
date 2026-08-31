<?php
/**
 * Component: FAQ
 * Expects $content variable from engine.php
 */
$title = $content['title'] ?? 'Veelgestelde vragen';
$items = $content['items'] ?? [];
?>
<section class="slide" data-nav-theme="dark">
    <div class="slide__bg" style="background-color: var(--purple);"></div>
    <div class="slide__content faq-section" style="padding-top:4rem; padding-bottom:6rem;">
        <h4 style="margin-bottom: 1.5rem; margin-top: 3rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= htmlspecialchars($title) ?></h4>
        
        <div class="prototype-faq">
            <?php foreach ($items as $item): ?>
                <details class="prototype-faq-item">
                    <summary>
                        <?= htmlspecialchars($item['question']) ?> 
                        <span class="prototype-faq-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>
                    <div class="prototype-faq-content">
                        <p><?= htmlspecialchars($item['answer']) ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
            
            <?php if (empty($items)): ?>
                <!-- Default mockup als er nog geen FAQ items in JSON staan -->
                <details class="prototype-faq-item">
                    <summary>Hoe werkt het CMS? <span class="prototype-faq-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg></span></summary>
                    <div class="prototype-faq-content">
                        <p>Voeg FAQ items toe via de Admin interface in het JSON veld. Gebruik de structuur: <code>"items": [{"question": "...", "answer": "..."}]</code>.</p>
                    </div>
                </details>
            <?php endif; ?>
        </div>
    </div>
</section>
