<h3 style="margin-top: 3rem; margin-bottom: 1.5rem; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t('prototype.short_route.title', 'Ons traject in het kort'); ?></h3>
                <div class="modal-meta-list" style="margin-top: 0; margin-bottom: 3rem;">
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Doorlooptijd:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.time', '5 werkdagen'); ?></span>
                    </div>
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Aanpak:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.approach', 'Snel van ontwerp naar validatie'); ?></span>
                    </div>
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Expertise:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.expertise', 'Brainstorms, UX design, AI development, usertests'); ?></span>
                    </div>
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Inzet:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.effort', '80 uur inzet door experts'); ?></span>
                    </div>
                    <div class="modal-meta-item">
                        <span class="modal-meta-label">Resultaat:</span>
                        <span class="modal-meta-value"><?= t('prototype.short_route.result', 'Door klant getest product'); ?></span>
                    </div>
                </div>
                <h3 style="margin-top: 3rem; margin-bottom: 12px; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t('prototype.ai_power.title', 'AI-kracht samen met ervaren regisseurs'); ?></h3>
                <p style="margin-bottom: 1.5rem; color: #ffffff; line-height: 1.6;"><?= t('prototype.ai_power.intro', 'Dankzij de combinatie van Figma en moderne AI-development (zoals Claude Code en Antigravity) bouwen we in dagen wat voorheen maanden kostte. Iets in elkaar zetten met AI is tegenwoordig niet zo moeilijk meer; de echte uitdaging zit in structuur, kwaliteit en haalbaarheid. Met onze ontwikkelervaring zorgen we voor een schaalbare architectuur, een behapbare scope en een resultaat dat implementeerbaar is.'); ?></p>
                <ul style="list-style: none; padding: 0; margin-bottom: 2rem; color: #ffffff; line-height: 1.6;">
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.ai_power.item1', 'Schaalbare design systems: herbruikbare componenten'); ?></li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.ai_power.item2', 'Veilige code-omgeving: draait op eigen servers'); ?></li>
                    <li style="margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.ai_power.item3', 'Gestructureerde workflows: strakke kaders en processen'); ?></li>
                    <li style="display: flex; align-items: flex-start; gap: 8px;"><span style="color: var(--color-green, #10b981);">✓</span> <?= t('prototype.ai_power.item4', 'Scherpe inventarisatie: overzicht bij complexe belangen'); ?></li>
                </ul>

                <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 3rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.7); font-weight: 500; font-size: 0.95rem;">
                        <svg width="18" height="24" viewBox="0 0 38 57" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 28.5C19 33.7467 14.7467 38 9.5 38C4.25329 38 0 33.7467 0 28.5C0 23.2533 4.25329 19 9.5 19H19V28.5Z" fill="#0ACF83"/><path d="M0 47.5C0 52.7467 4.25329 57 9.5 57C14.7467 57 19 52.7467 19 47.5V38H9.5C4.25329 38 0 42.2533 0 47.5Z" fill="#1ABCFE"/><path d="M38 9.5C38 14.7467 33.7467 19 28.5 19H19V0H28.5C33.7467 0 38 4.25329 38 9.5Z" fill="#FF7262"/><path d="M0 9.5C0 14.7467 4.25329 19 9.5 19H19V0H9.5C4.25329 0 0 4.25329 0 9.5Z" fill="#F24E1E"/><path d="M38 28.5C38 33.7467 33.7467 38 28.5 38C23.2533 38 19 33.7467 19 28.5C19 23.2533 23.2533 19 28.5 19C33.7467 19 38 23.2533 38 28.5Z" fill="#A259FF"/></svg>
                        <span>Figma Make</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.7); font-weight: 500; font-size: 0.95rem;">
                        <img src="../assets/claude-logo.png" alt="Claude" style="width: 22px; height: 22px; object-fit: contain;">
                        <span>Claude Code</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.7); font-weight: 500; font-size: 0.95rem;">
                        <img src="../assets/antigravity-logo.png" alt="Antigravity" style="width: 24px; height: 24px; object-fit: contain;">
                        <span>Antigravity (Google)</span>
                    </div>
                </div>

                <div style="position: relative; width: 100%; border-radius: 16px; overflow: hidden; margin-bottom: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                    <img src="../assets/content-afbeelding-11.webp" class="tall-on-mobile" alt="Team aan het werk" loading="lazy" decoding="async" width="1600" height="900" style="width: 100%; aspect-ratio: 16/9; display: block; object-fit: cover;">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); display: flex; align-items: center; justify-content: center; padding: 2rem; text-align: center;">
                        <h3 style="color: #ffffff; font-size: 1.8rem; font-weight: 600; font-family: var(--font-heading); margin: 0; line-height: 1.2; max-width: 90%;"><?= t('prototype.highlight1.text', 'Iedereen kan code genereren, maar wij zorgen voor toegevoegde waarde voor jouw klant'); ?></h3>
                    </div>
                </div>

                <h3 style="margin-top: 3rem; margin-bottom: 1.5rem; font-size: 1.4rem; font-weight: 600; font-family: var(--font-heading); color: rgba(255,255,255,0.9);"><?= t('prototype.deliverables.title', 'Wat kun je verwachten?'); ?></h3>
                <!-- Opsomming blok -->
                <div class="prototype-columns-block">
                    <div class="column">
                        <h4><?= t('prototype.deliverables.col1.title', 'Wat leveren we'); ?></h4>
                        <ul>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col1.item1', 'Concreet actieplan'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col1.item2', 'Klikbaar prototype'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col1.item3', 'Echte gebruikerstesten'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col1.item4', 'Werkende applicatie'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.hero.tag7', '80 ontwikkeluren'); ?></li>
                        </ul>
                    </div>
                    <div class="divider"></div>
                    <div class="column">
                        <h4><?= t('prototype.deliverables.col2.title', 'De waarde voor jou'); ?></h4>
                        <ul>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item1', 'Zekerheid in 5 dagen'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item2', 'Minimaal ontwikkelrisico'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item3', 'Direct klantinzicht'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item4', 'Kennis van MVP-denken'); ?></li>
                            <li><span class="check">✓</span> <?= t('prototype.deliverables.col2.item5', 'Kennis van AI'); ?></li>
                        </ul>
                    </div>
                </div>

                