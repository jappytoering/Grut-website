<?php
require_once __DIR__ . '/../includes/db_helper.php';

$pdo = get_cms_connection();

try {
    $pdo->beginTransaction();

    // Verwijder oude seed als die bestaat
    $pdo->exec("DELETE FROM page_blocks WHERE page_id IN (SELECT id FROM pages WHERE slug = 'prototype-sprint-v2')");
    $pdo->exec("DELETE FROM pages WHERE slug = 'prototype-sprint-v2'");

    // 1. Maak de pagina aan
    $stmt = $pdo->prepare("INSERT INTO pages (slug, template, status, seo_title, meta_description) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        'prototype-sprint-v2',
        'page-shell',
        'draft',
        'Prototype Sprint | Grut Designers',
        'In 5 dagen een werkend product'
    ]);
    
    $pageId = $pdo->lastInsertId();

    $blocks = [];
    $order = 0;

    // 1. Sprint Header (CSS and layout wrapper)
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_header',
        'sort_order' => $order++,
        'content_json' => json_encode([])
    ];

    // 2. Sprint Hero
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_hero',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'title' => 'In 5 dagen een werkend product',
            'intro' => 'In een razendsnel tempo van vijf dagen transformeren we jouw concept in een tastbaar, klikbaar en getest prototype...',
            'tags' => ['1 week', 'Jouw kantoor', 'Hackathon', 'AI powered', 'All-in-prijs', 'Met echte klanten', '80 ontwikkeluren']
        ])
    ];

    // 3. Sprint Slider
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_slider',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'style' => 'hero-auto',
            'slides' => [
                ['image_url' => '../assets/sprint-slider-1.jpg', 'title' => 'Met Figma, Claude code en Google tools'],
                ['image_url' => '../assets/sprint-slider-2.jpg', 'title' => 'Van idee naar klikbaar prototype'],
                ['image_url' => '../assets/sprint-slider-3.jpg', 'title' => 'Validatie bij echte klanten']
            ]
        ])
    ];

    // 4. Text Checklist
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_text_checklist',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'title' => 'Hoe het werkt?',
            'intro' => 'Samen met jouw team...',
            'checklist' => [
                'Gefocust sprinttraject: In 5 dagen gaan we van denkwerk naar een getest en gevalideerd product',
                'Korte lijnen: we schakelen dagelijks met jouw medewerkers, klanten en belangrijke stakeholders',
                'Samen sturen: we houden presentatiemomenten om feedback om te zetten naar vervolgstappen'
            ]
        ])
    ];

    // 5. Meta List
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_meta_list',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'title' => 'Ons traject in het kort',
            'items' => [
                ['label' => 'Doorlooptijd:', 'value' => '5 werkdagen'],
                ['label' => 'Aanpak:', 'value' => 'Snel van ontwerp naar validatie'],
                ['label' => 'Resultaat:', 'value' => 'Door klant getest product']
            ]
        ])
    ];

    // 6. Highlight Block
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_highlight_block',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'title' => '10 jaar ervaring in usertests en softwareanalyses',
            'checklist' => ['Data-analyses', 'Meerdere testprincipes', 'Via Teams'],
            'image_url' => '../assets/strategie2-1200x800.webp'
        ])
    ];

    // 7. Columns Block
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_columns_block',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'title_left' => 'Wat leveren we',
            'items_left' => ['Concreet actieplan', 'Klikbaar prototype'],
            'title_right' => 'De waarde voor jou',
            'items_right' => ['Zekerheid in 5 dagen', 'Minimaal ontwikkelrisico']
        ])
    ];

    // 8. Days List
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_days_list',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'title' => 'Het proces',
            'steps' => [
                ['day_label' => 'Dag 1', 'title' => 'Strategie & Inventarisatie', 'description' => 'We beginnen met een kick-off...'],
                ['day_label' => 'Dag 2', 'title' => 'Ontwerpen', 'description' => 'De eerste ontwerpen worden gemaakt...']
            ]
        ])
    ];

    // 9. FAQ
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_faq',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'title' => 'Veelgestelde vragen',
            'faqs' => [
                ['question' => 'Wat kost het?', 'answer' => 'We werken met een vaste prijs.'],
                ['question' => 'Moeten we zelf aanwezig zijn?', 'answer' => 'Ja, op dag 1 en 5.']
            ]
        ])
    ];

    // 10. Trusted Logos
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_trusted_logos',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'title' => 'Zij gingen je voor',
            'logos' => [
                '../assets/Logo/G/Logo_grut_wit.svg',
                '../assets/Logo/G/Logo_grut_wit.svg'
            ]
        ])
    ];

    // 11. CTA Form
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_cta_block',
        'sort_order' => $order++,
        'content_json' => json_encode([
            'title' => 'Klaar om te starten?',
            'price' => '€5.000',
            'checklist' => ['Vaste prijs', 'Geen verrassingen'],
            'form_id' => 1
        ])
    ];

    // 12. Sprint Footer (Closing tags and scripts)
    $blocks[] = [
        'page_id' => $pageId,
        'block_type' => 'sprint_footer',
        'sort_order' => $order++,
        'content_json' => json_encode([])
    ];

    $stmt_block = $pdo->prepare("INSERT INTO page_blocks (page_id, block_type, sort_order, content_json) VALUES (?, ?, ?, ?)");
    foreach ($blocks as $b) {
        $stmt_block->execute([
            $b['page_id'],
            $b['block_type'],
            $b['sort_order'],
            $b['content_json']
        ]);
    }

    $pdo->commit();
    echo "Seed voltooid voor prototype-sprint-v2.\n";

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Fout bij seeden: " . $e->getMessage() . "\n";
}
