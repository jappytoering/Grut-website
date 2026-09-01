<div class="overlay-header__tags" style="margin-bottom: 22px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; color: #C7CBD1; font-family: var(--font-body); font-size: calc(var(--body-size) - 3px); font-weight: 400; letter-spacing: 0.5px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                    <span>Nieuw</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>Sprinttraject</span>
                </div>
            </div>
            <h3 class="overlay-title" style="margin-bottom: 12px;"><?= htmlspecialchars($content['title'] ?? 'In 5 dagen een werkend product') ?></h3>
            <div class="overlay-flexible-content">
                <p class="overlay-intro"><?= htmlspecialchars($content['intro'] ?? 'In een razendsnel tempo van vijf dagen transformeren we jouw concept in een tastbaar, klikbaar en getest prototype. We halen de ruis weg, focussen op de kern en zorgen dat je al in korte tijd bewijs hebt van wat wel en niet werkt voor jouw doelgroep. Geen eindeloze vergaderingen, maar direct actie en resultaat. Bruist jouw team van de ideeën, maar mis je de vaart om ze echt te testen? Ontdek hieronder hoe we in vijf dagen van concept naar prototype gaan.') ?></p>
                
                <div class="overlay-content__tags">
                    <?php 
                    $tags = array_map('trim', explode(',', $content['tags'] ?? '1 week, Jouw kantoor, Hackathon, AI powered, All-in-prijs, Met echte klanten, 80 ontwikkeluren'));
                    
                    // Simple SVGs mapping for the demo. In a real scenario we'd have a tag to icon mapping.
                    $icons = [
                        '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
                        '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
                        '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
                        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-yellow);"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M7 5H3"/><path d="M19 17v4"/><path d="M21 19h-4"/></svg>',
                        '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-yellow);"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="12" y1="2" x2="12" y2="6"/></svg>',
                        '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
                        '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" style="color: var(--color-yellow);"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>'
                    ];
                    
                    foreach ($tags as $index => $tag):
                        if (empty($tag)) continue;
                        $icon = $icons[$index % count($icons)];
                    ?>
                    <span class="overlay-header__tag" style="display: inline-flex; align-items: center; gap: 6px;">
                        <?= $icon ?>
                        <?= htmlspecialchars($tag) ?>
                    </span>
                    <?php endforeach; ?>
                </div>

                