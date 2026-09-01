<?php
// Script to migrate overlays into database
require_once __DIR__ . '/../includes/db_helper.php';
$pdo = get_cms_connection();
$overlays = [
    [
        'slug' => 'overlay-diensten-strategie',
        'seo_title' => 'Digitale Strategie Overlay',
        'blocks' => [
            ['type' => 'text_block', 'content' => ['is_intro' => 'true', 'text' => 'Geen ontwerp zonder goed plan. Veel ervaring met het vertalen van grote ambities naar behapbare, impactvolle verbeteringen. Een succesvol digitaal product begint namelijk niet bij code of design, maar bij de juiste vragen. Met scherpe strategie en analyse leggen we de lat direct hoog. We doorgronden de markt, begrijpen de eindgebruiker en bepalen exact waar de kansen liggen. Zo bouwen we samen aan een fundament dat vanaf dag één resultaat levert.']],
            ['type' => 'tags_list', 'content' => ['tags' => 'Digitale strategie, Interviews, Testpanels, SCRUM planning, Roadmaps, Prioritering, Stakeholdermanagement, Hei-sessies, Customer Journeys, Marketingstrategie, Research, Analytics']],
            ['type' => 'image_block', 'content' => ['image' => 'assets/content-afbeelding-8.webp?v=4', 'alt' => 'Strategie en Analyse fundament']],
            ['type' => 'meta_list', 'content' => ['title' => 'In het kort', 'label_1' => 'Ervaring in:', 'value_1' => 'Retail, e-commerce, bouw, onderwijs, semi-overheid', 'label_2' => 'Opdrachten:', 'value_2' => 'Interim & projecten', 'label_3' => 'Resultaat:', 'value_3' => 'Onderzoeksrapport, customer journeys, roadmaps, plan van aanpak, begroting', 'label_4' => 'Rollen:', 'value_4' => 'Strateeg, UX-researcher, Productowner']],
            ['type' => 'text_block', 'content' => ['title' => 'Ervaring in diverse branches', 'text' => "Met meer dan 10 jaar ervaring in media en marketing, verspreid over de bouw, retail, e-commerce en het onderwijs, van startup tot corporate, komen we snel tot de kern. Onze aanpak? Praktisch en doelgericht. Binnen enkele uren of sessies leggen we bloot waar de echte knelpunten zitten.\n\nGeen dikke adviesrapporten die in een lade verdwijnen, maar de vinger op de zere plek én een helder actieplan met concrete verbeterstappen. Minder lullen, meer poetsen."]],
            ['type' => 'bullet_list', 'content' => ['bullets' => "Snel inzichtelijk waar kansen en uitdagingen liggen\nScherp zicht op optimalisatiekosten versus beschikbare budgetten\nRuime ervaring met verandertrajecten binnen IT- en marketingorganisaties"]],
            ['type' => 'image_block', 'content' => ['image' => 'assets/content-afbeelding-4.webp?v=4', 'alt' => 'Strategie en Analyse']],
            ['type' => 'text_block', 'content' => ['text' => 'Vanuit inzicht adviseren we doelgericht naar de vervolgstappen. Dit brengt doelen, doelgroep en techniek samen tot één duidelijke koers die richting geeft aan ontwerp, ontwikkeling en marketing.']],
            ['type' => 'text_block', 'content' => ['title' => 'Welke bedrijven passen ons het beste?', 'text' => 'Van ambitieuze scale-ups tot nationale kampioenen. Wij werken het liefst voor bedrijven met een digitaal platform waarvan ze voelen: hier zit simpelweg meer in. Wij stappen in op het punt waar creativiteit en stootkracht nodig zijn. Vage ideeën en grote ambities omzetten in concrete, haalbare resultaten, daar krijgen wij energie van. En het liefst doen we dat voor organisaties die de wereld nét een stukje mooier, slimmer of duurzamer maken.']],
            ['type' => 'text_block', 'content' => ['title' => 'Hoe ziet een traject eruit?', 'text' => "Het begint altijd met een vrijblijvende kennismaking. We voeren vooraf een UX scan uit van je website en presenteren in dit gesprek direct de belangrijkste knelpunten én kansen.\n\nSamen stemmen we af welke samenwerkingsvorm het beste bij jouw organisatie past. Dat is sterk afhankelijk van hoe je huidige marketing- en softwareteams zijn ingericht. We werken op projectbasis met een vooraf helder afgebakende scope, óf we vullen tijdelijk specifieke rollen in binnen je team.\n\nWe geloven in duurzame samenwerkingen en blijven op de lange termijn betrokken. Met testpanels, heldere rapportages en periodiek advies houden we de vinger aan de pols. Kwartaalgesprekken vormen hierin de rode draad: vanuit daar sturen we continu bij wat er nodig is om samen je doelen te behalen."]],
            ['type' => 'text_block', 'content' => ['title' => 'En nu?', 'text' => 'Klaar om ambities te vertalen naar een concreet plan? Plan zo snel mogelijk een vrijblijvende kennismaking! Laten we samen de basis leggen voor een product dat écht werkt.']],
            ['type' => 'overlay_cta', 'content' => ['title' => 'Wij denken graag<br>met je mee', 'subtitle' => 'Stappen maken met je digitale omgeving?']]
        ]
    ],
    [
        'slug' => 'overlay-case-sanoma',
        'seo_title' => 'Case: Sanoma',
        'blocks' => [
            ['type' => 'text_block', 'content' => ['is_intro' => 'true', 'text' => '‘Hoe kunnen we nieuwe digitale proposities ontwikkelen, bespreken met gebruikers en intern succesvol laten landen?’. Binnen het innovatieteam van Sanoma Learning werkte Jurrit aan het ontwerpen, testen en valideren van verschillende digitale concepten en proposities voor het voortgezet onderwijs. De focus lag op het ontwikkelen van nieuwe digitale innovaties die richting geven aan toekomstige producten en diensten voor Sanoma.']],
            ['type' => 'tags_list', 'content' => ['tags' => 'Stakeholder management, usertests, validaties, analytics, UX design, concepting, prototyping, Figma, interim, presentaties, SCRUM']],
            ['type' => 'text_block', 'content' => ['text' => 'Samen met multidisciplinaire teams onderzocht ik nieuwe kansen, werkte ik ideeën uit naar concrete concepten en vertaalde ik complexe vraagstukken naar visuele praatplaten, prototypes en interactieve flows. Hierbij stond het MVP toetsen bij gebruikers centraal, zodat ideeën niet alleen intern gedragen werden, maar ook daadwerkelijk aansloten op de behoeften van docenten.']],
            ['type' => 'image_block', 'content' => ['image' => 'assets/content-afbeelding-11.webp', 'alt' => 'Sanoma AI Tool']],
            ['type' => 'text_block', 'content' => ['title' => 'Doel', 'text' => 'Het ontwikkelen en valideren van nieuwe digitale proposities die bijdragen aan toekomstige innovaties binnen het onderwijs, met focus op het verbeteren van de digitale leer- en gebruikerservaring voor leerlingen en docenten.']],
            ['type' => 'text_block', 'content' => ['title' => 'Gewenst resultaat', 'text' => 'Het opleveren van concepten, praatplaten en interactieve prototypes waarmee nieuwe digitale proposities onderzocht, getest en gevalideerd konden worden bij de gebruikers. Daarnaast lag de focus op het creëren van intern draagvlak voor digitale innovatie door stakeholders actief mee te nemen in het gehele proces en mee te laten denken over deze plannen.']],
            ['type' => 'overlay_cta', 'content' => ['title' => 'Wij denken graag<br>met je mee', 'subtitle' => 'Stappen maken met je digitale omgeving?']]
        ]
    ]
];

foreach ($overlays as $o) {
    // Delete if exists
    $stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = ?");
    $stmt->execute([$o['slug']]);
    $existing = $stmt->fetchColumn();
    if ($existing) {
        $pdo->exec("DELETE FROM page_blocks WHERE page_id = $existing");
        $pdo->exec("DELETE FROM pages WHERE id = $existing");
    }

    $stmt = $pdo->prepare("INSERT INTO pages (slug, template, status, seo_title, created_at) VALUES (?, 'overlay', 'published', ?, CURRENT_TIMESTAMP)");
    $stmt->execute([$o['slug'], $o['seo_title']]);
    $page_id = $pdo->lastInsertId();

    $sort = 1;
    foreach ($o['blocks'] as $block) {
        $b_stmt = $pdo->prepare("INSERT INTO page_blocks (page_id, block_type, sort_order, content_json, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $b_stmt->execute([$page_id, $block['type'], $sort++, json_encode($block['content'])]);
    }
    echo "Ingevoegd: " . $o['slug'] . "\n";
}
echo "Klaar.\n";
