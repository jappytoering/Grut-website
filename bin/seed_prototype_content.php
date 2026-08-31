<?php
$dbPath = __DIR__ . '/../storage/content.sqlite';

if (!file_exists($dbPath)) {
    echo "Run init-db.php first to create the database.\n";
    exit(1);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$content = [
    'prototype.header.title' => 'Prototype sprint',
    'prototype.hero.title' => 'In 5 dagen een werkend product',
    'prototype.hero.intro' => 'In een razendsnel tempo van vijf dagen transformeren we jouw concept in een tastbaar, klikbaar en getest prototype. We halen de ruis weg, focussen op de kern en zorgen dat je al in korte tijd bewijs hebt van wat wel en niet werkt voor jouw doelgroep. Geen eindeloze vergaderingen, maar direct actie en resultaat. Bruist jouw team van de ideeën, maar mis je de vaart om ze echt te testen? Ontdek hieronder hoe we in vijf dagen van concept naar prototype gaan.',
    'prototype.hero.tag1' => '1 week',
    'prototype.hero.tag2' => 'Jouw kantoor',
    'prototype.hero.tag3' => 'Hackathon',
    'prototype.hero.tag4' => 'AI powered',
    'prototype.hero.tag5' => 'All-in-prijs',
    'prototype.hero.tag6' => 'Met echte klanten',
    'prototype.hero.tag7' => '80 ontwikkeluren',
    
    'prototype.slider1.title' => 'Met Figma, Claude code en Google tools',
    'prototype.slider2.title' => 'Van idee naar klikbaar prototype',
    'prototype.slider3.title' => 'Validatie bij echte klanten',
    'prototype.slider4.title' => 'Experts met 10 jaar ervaring',
    'prototype.slider5.title' => 'Onze ervaring gecombineerd met jullie kennis',
    
    'prototype.how_it_works.title' => 'Hoe het werkt?',
    'prototype.how_it_works.intro' => 'Samen met jouw team, onze jarenlange ontwikkelervaring en de kracht van generative AI brengen we jouw idee, bij jou op kantoor, in vijf dagen tot leven. Bovendien testen we het gelijk met medewerkers en klanten:',
    'prototype.how_it_works.item1' => 'Gefocust sprinttraject: In 5 dagen gaan we van denkwerk naar een getest en gevalideerd product',
    'prototype.how_it_works.item2' => 'Korte lijnen: we schakelen dagelijks met jouw medewerkers, klanten en belangrijke stakeholders',
    'prototype.how_it_works.item3' => 'Samen sturen: we houden presentatiemomenten om feedback om te zetten naar vervolgstappen',
    'prototype.how_it_works.item4' => 'Echte feedback: we houden interviews met jouw klanten en laten hen het prototype testen.',
    'prototype.how_it_works.item5' => 'Tastbaar resultaat: je eindigt met een werkend product én waardevolle nieuwe inzichten',
    
    'prototype.value.title' => 'Waarde toevoegen binnen enkele uren',
    'prototype.value.intro' => 'We hebben een programma samengesteld waarmee we maximale impact maken op jouw organisatie. Gevormd door onze jarenlange ervaring in developmentteams, combineren we brainstormtechnieken met de SCRUM-werkwijze en de MVP-aanpak. We brengen complexe eisen en belangen snel in kaart, schatten de toegevoegde waarde in en valideren de software direct bij echte eindgebruikers.',
    
    'prototype.customers.title' => 'Werken met echte klanten',
    'prototype.customers.intro' => 'Je hebt een idee, maar mist de tijd of capaciteit om uit te zoeken of het écht aanslaat. Voordat je investeert in een langdurig ontwikkeltraject, wil je zeker weten of je doelgroep erop zit te wachten. Door het product direct voor te leggen aan jouw klanten, toetsen we razendsnel de waarde en krijgen we inzicht in de wensen van jouw doelgroep. Die feedback verwerken we direct in korte ontwikkelloops naar een optimale versie.',
    
    'prototype.customers.tag1' => 'Testpanels',
    'prototype.customers.tag2' => 'Analytics',
    'prototype.customers.tag3' => 'Enquêtes',
    'prototype.customers.tag4' => 'Interviews',
    
    'prototype.short_route.title' => 'Ons traject in het kort',
    'prototype.short_route.time' => '5 werkdagen',
    'prototype.short_route.approach' => 'Snel van ontwerp naar validatie',
    'prototype.short_route.expertise' => 'Brainstorms, UX design, AI development, usertests',
    'prototype.short_route.effort' => '80 uur inzet door experts',
    'prototype.short_route.result' => 'Door klant getest product',
    
    'prototype.ai_power.title' => 'AI-kracht samen met ervaren regisseurs',
    'prototype.ai_power.intro' => 'Dankzij de combinatie van Figma en moderne AI-development (zoals Claude Code en Antigravity) bouwen we in dagen wat voorheen maanden kostte. Iets in elkaar zetten met AI is tegenwoordig niet zo moeilijk meer; de echte uitdaging zit in structuur, kwaliteit en haalbaarheid. Met onze ontwikkelervaring zorgen we voor een schaalbare architectuur, een behapbare scope en een resultaat dat implementeerbaar is.',
    'prototype.ai_power.item1' => 'Schaalbare design systems: herbruikbare componenten',
    'prototype.ai_power.item2' => 'Veilige code-omgeving: draait op eigen servers',
    'prototype.ai_power.item3' => 'Gestructureerde workflows: strakke kaders en processen',
    'prototype.ai_power.item4' => 'Scherpe inventarisatie: overzicht bij complexe belangen',
    
    'prototype.highlight1.text' => 'Iedereen kan code genereren, maar wij zorgen voor toegevoegde waarde voor jouw klant',
    
    'prototype.deliverables.title' => 'Wat kun je verwachten?',
    'prototype.deliverables.col1.title' => 'Wat leveren we',
    'prototype.deliverables.col1.item1' => 'Concreet actieplan',
    'prototype.deliverables.col1.item2' => 'Klikbaar prototype',
    'prototype.deliverables.col1.item3' => 'Echte gebruikerstesten',
    'prototype.deliverables.col1.item4' => 'Werkende applicatie',
    'prototype.deliverables.col1.item5' => '80 ontwikkeluren',
    'prototype.deliverables.col2.title' => 'De waarde voor jou',
    'prototype.deliverables.col2.item1' => 'Zekerheid in 5 dagen',
    'prototype.deliverables.col2.item2' => 'Minimaal ontwikkelrisico',
    'prototype.deliverables.col2.item3' => 'Direct klantinzicht',
    'prototype.deliverables.col2.item4' => 'Kennis van MVP-denken',
    'prototype.deliverables.col2.item5' => 'Kennis van AI',
    
    'prototype.fit.title' => 'Is jouw kwestie geschikt voor de sprintvorm?',
    'prototype.fit.intro' => 'De sprint is ideaal voor het valideren van slimme tools en flows: van configurator, rekentool of keuzehulp tot gestroomlijnde onboarding, vernieuwde checkouts en handige AI-features. Twijfel je of jouw idee hier tussen past? Mail ons jouw idee. In een korte gezamenlijke haalbaarheidscheck schatten we snel in of het concept geschikt is voor ons programma.',
    'prototype.fit.item1' => 'Perfect voor offertetools, onboarding en de digitalisering van processen',
    'prototype.fit.item2' => 'We doen altijd een inschatting op haalbaarheid',
    'prototype.fit.item3' => 'Veel ervaring in het inschatten van ontwikkelscopes',
    
    'prototype.highlight2.title' => '10 jaar ervaring in usertests en softwareanalyses',
    'prototype.highlight2.item1' => 'Data-analyses',
    'prototype.highlight2.item2' => 'Meerdere testprincipes',
    'prototype.highlight2.item3' => 'Via Teams',
    
    'prototype.program.title' => 'Programma',
    'prototype.program.day1.label' => 'Dag 1',
    'prototype.program.day1.title' => 'Verkenning & brainstorm',
    'prototype.program.day1.content' => 'We trappen samen af. Wat is de kern van je idee, voor wie lossen we een probleem op en welke cruciale vragen willen we na vijf dagen beantwoord hebben? We bepalen de scope en scherpen de doelstelling aan.',
    'prototype.program.day2.label' => 'Dag 2',
    'prototype.program.day2.title' => 'Schetsen, ontdekken & bouwen',
    'prototype.program.day2.content' => 'Wij gaan aan de slag achter de schermen. We vertalen de input naar een doordacht UX-concept, schetsen de belangrijkste user flows en selecteren de juiste tech-stack en AI-tools om de bouw te versnellen.',
    'prototype.program.day3.label' => 'Dag 3',
    'prototype.program.day3.title' => 'Interne validatie & doorontwikkeling',
    'prototype.program.day3.content' => 'Tijd om het idee tot leven te wekken. Met behulp van moderne AI-coding bouwen we in één dag een volledig interactief, klikbaar en werkend prototype op het gekozen device (mobiel of desktop).',
    'prototype.program.day4.label' => 'Dag 4',
    'prototype.program.day4.title' => 'Usertesting & Advies',
    'prototype.program.day4.content' => 'We leggen het prototype voor aan echte eindgebruikers uit jouw doelgroep. Wat snappen ze meteen? Waar haken ze af? En vooral: lost dit hun probleem écht op? We verzamelen ongefilterde data en reacties.',
    'prototype.program.day5.label' => 'Dag 5',
    'prototype.program.day5.title' => 'MVP & Next steps',
    'prototype.program.day5.content' => 'We zetten alle testresultaten en data op een rij. Tijdens een interactieve eindpresentatie laten we zien wat werkt, waar kansen liggen en geven we een onderbouwd advies: vol doorontwikkelen, gericht bijsturen of direct stoppen. Uiteraard leveren we de software op zodat jij het kan gebruiken.',
    
    'prototype.we_bring.title_start' => 'Wat wij',
    'prototype.we_bring.title_end' => 'inbrengen?',
    'prototype.we_bring.intro' => 'Met Grut haal je meer dan tien jaar hands-on ervaring per persoon in huis op het snijvlak van strategische UX, online marketing en productontwikkeling. We hebben talloze digitale projecten geleid en weten precies hoe je complexe vraagstukken terugbrengt tot de essentie. We combineren diepgaande ontwerpkennis met slimme AI-development om snel, scherp en zonder omwegen echte waarde voor jouw doelgroep te creëren.',
    'prototype.we_bring.item1' => '2 experts met 10 jaar ervaring in softwareontwikkeling',
    'prototype.we_bring.item2' => 'In educatie, bouw, retail en e-commerce',
    'prototype.we_bring.item3' => 'Kennis van AI development, UX design en online marketing',
    
    'prototype.highlight3.text' => 'Design ambacht in combi met generative AI',
    
    'prototype.faq.title' => 'Veelgestelde vragen',
    'prototype.faq.q1' => 'Is er veel voorkennis nodig van AI?',
    'prototype.faq.a1' => 'Nee. Jij brengt de kennis in over jouw bedrijf, product en markt. Wij combineren dat met onze expertise in marketing, UX design, development en AI. In de eerste dagen ontdekken we samen de kern van de casus. Daarna gaan we bouwen en scherpen we het concept direct aan met feedback van jouw medewerkers en klanten.',
    'prototype.faq.q2' => 'Wat als het niet afkomt?',
    'prototype.faq.a2' => 'We bouwen vaste checkmomenten in tijdens de sprint. Mocht blijken dat een idee toch niet haalbaar is, dan kunnen we op tijd bijsturen of het project stopzetten. Ons uitgangspunt is vertrouwen: we geloven sterk in deze werkwijze en zetten alles op alles om in één week een waardevol resultaat neer te zetten. Mocht er iets onvoorziens gebeuren, dan lossen we dat altijd in goed overleg op.',
    'prototype.faq.q3' => 'Kunnen jullie na deze 5 dagen verder helpen?',
    'prototype.faq.a3' => 'Absoluut. Het geteste prototype is geen eindpunt, maar het fundament. We sluiten de sprint af met een concreet vervolgadvies en een heldere roadmap voor de technische realisatie. Vanuit daar kunnen we het product direct samen met jou doorontwikkelen tot een volwaardige productie-app, of de documentatie, het design system en de code naadloos overdragen aan jouw eigen developmentteam.',
    'prototype.faq.q4' => 'Hebben jullie dit jaar nog tijd?',
    'prototype.faq.a4' => 'We doen maximaal 20 van deze intensieve trajecten per jaar, zodat we elk project met 100% focus en energie kunnen draaien. We hebben dit jaar nog een beperkt aantal plekken beschikbaar. Daarom doen we vooraf altijd een korte haalbaarheidscheck: we stappen alleen in als we overtuigd zijn van een grote slagingskans.',
    'prototype.faq.q5' => 'Hoeveel tijd ben ik zelf kwijt aan dit project?',
    'prototype.faq.a5' => 'Reken op ongeveer twee gezamenlijke werksessies van elk een dagdeel. Daarnaast stemmen we 4 tot 5 keer per week een halfuurtje af met jou of een betrokken medewerker, en voeren we korte gesprekken met een aantal klanten. We richten een gedeeld kanaal (zoals WhatsApp of Teams) in om snel data en feedback uit te wisselen. De voorbereiding kost je circa 2 uur. Uiteraard geldt: hoe meer tijd en input jij erin steekt, hoe rijker het eindresultaat.',
    'prototype.faq.q6' => 'Wat kost het?',
    'prototype.faq.a6' => 'De investering bedraagt € 4.999,- (excl. btw). Gevestigd in Fryslân? Maak dan gebruik van de Adviesvoucherregeling Fryslân (SNN) om 50% subsidie te ontvangen, waardoor de sprint je slechts € 2.499,- kost.',
    
    'prototype.investment.title' => 'Investering',
    'prototype.investment.money_val' => '€ 4.999,-',
    'prototype.investment.prep_val' => '1 à 2 uurtjes',
    'prototype.investment.time_val' => 'Enkele dagdelen (in de sprint) hebben we jouw kennis nodig.',
    
    'prototype.doubts.title' => 'Twijfel je nog?',
    'prototype.doubts.p1' => 'We worden altijd enthousiast van ambitieuze plannen en denken graag vrijblijvend met je mee. Wie weet levert een kort gesprek je direct waardevolle nieuwe inzichten op. Stuur een appje of een mailtje naar letsgo@grutdesigners.nl en we nemen snel contact op.',
    'prototype.doubts.p2' => 'Ben jij Friese MKB ondernemer? Maak dan gebruik van de Adviesvoucherregeling (aangeboden door SNN) voor innovatietrajecten en krijg 50% korting op het traject.',
    'prototype.doubts.p3' => 'Let op: We hebben dit jaar nog capaciteit voor 3 trajecten, dus wees er snel bij 🚀',
    
    'prototype.trusted.title' => 'Wij werkten al voor:'
];

$stmtKey = $pdo->prepare("INSERT INTO content_keys (key_name) VALUES (:key_name) ON CONFLICT(key_name) DO NOTHING");
$stmtSelectKey = $pdo->prepare("SELECT id FROM content_keys WHERE key_name = :key_name");
$stmtTrans = $pdo->prepare("INSERT INTO content_translations (key_id, locale, value) VALUES (:key_id, 'nl', :value)");
$stmtCheckTrans = $pdo->prepare("SELECT id FROM content_translations WHERE key_id = :key_id AND locale = 'nl'");
$stmtUpdate = $pdo->prepare("UPDATE content_translations SET value = :value WHERE key_id = :key_id AND locale = 'nl'");

foreach ($content as $key => $value) {
    // Insert key if not exists
    $stmtKey->execute([':key_name' => $key]);
    
    // Get key_id
    $stmtSelectKey->execute([':key_name' => $key]);
    $key_id = $stmtSelectKey->fetchColumn();
    
    // Check if translation exists
    $stmtCheckTrans->execute([':key_id' => $key_id]);
    if (!$stmtCheckTrans->fetchColumn()) {
        $stmtTrans->execute([
            ':key_id' => $key_id,
            ':value' => $value
        ]);
        echo "Inserted translation for {$key}\n";
    } else {
        $stmtUpdate->execute([
            ':key_id' => $key_id,
            ':value' => $value
        ]);
        echo "Updated translation for {$key}\n";
    }
}

echo "Seeding completed!\n";
