# Product Backlog: Grut Website 2026

Welkom bij de backlog van het Grut website project. We werken hier gestructureerd aan nieuwe functionaliteiten en verbeteringen.

## Werkregels voor samenwerking:
- We werken altijd strikt aan één story/taak tegelijk.
- Voordat je code schrijft voor een taak, stemmen we kort de aanpak af.
- Nadat een taak succesvol is gebouwd en getest, werk je de status in `BACKLOG.md` direct bij naar afgevinkt (`- [x]`).
- Raak geen bestanden of logica aan die horen bij toekomstige stories.

## Basisregels & Kaders (Altijd Toepassen)
**1. Grid- & Layoutstructuur**
- **Grid-consistentie:** Elk nieuw blok, formulier of overlay sluit strikt aan op het bestaande CSS Grid-systeem (kolommen, gutters, containers en max-widths).
- **Ruimte & Ritme:** Gebruik uitsluitend de vastgelegde spacing-tokens (margin/padding) voor een consistent verticaal en horizontaal ritme.
- **Responsive:** Volledig vloeiend schaalbaar op alle breakpoints (mobile-first) zonder horizontale scrollbars of ongewenste layout shifts.

**2. Huisstijl & Styling**
- **Design Tokens:** Maak uitsluitend gebruik van de bestaande CSS-variabelen voor kleuren, typografie, font-weights, border-radii en schaduwen. Geen losse, hardcoded hex-waarden of pixelgroottes.
- **Interactiestates:** Consistente styling voor `:hover`, `:focus-visible`, `:active` en `disabled` conform de gevestigde huisstijl.
- **Touch Targets:** Knoppen, checkboxes, radio-cards en pills hebben altijd een minimale klikbare zone van 44×44px op touchscreens.

**3. UX & Formulierinteractie**
- **Zichtbare Labels:** Elk invoerveld heeft een permanent zichtbaar label boven het veld (geen placeholders als vervanging van labels).
- **Mobiele Toetsenborden:** Correcte HTML-attributen per veldtype (`type="email"`, `type="tel"`, `autocomplete`, `inputmode`).
- **Vergevingsgezinde Invoer:** Telefoonnummers accepteren spaties, streepjes en landcodes zonder validatiefout; de backend normaliseert de data.
- **Inline Feedback & Foutafhandeling:** Validatie triggert pas bij `blur` of `submit`; foutmeldingen zijn concreet en oplossingsgericht.
- **Voorkom Dubbele Submits:** De submit-knop toont direct een laadstatus/spinner en wordt `disabled` tijdens het verzenden.

**4. Overlays & Toegankelijkheid (A11y)**
- **Toetsenbord & Focus:** Overlays sluiten altijd met de Escape-toets en houden de toetsenbordfocus vast (focus trap) zolang ze openstaan.
- **Scroll Lock:** De achterliggende pagina kan niet scrollen zolang een overlay actief is.
- **Screenreader Support:** Dynamische statusmeldingen (zoals succes- en foutboodschappen) gebruiken `aria-live="polite"`.

**5. Techniek & Veiligheid**
- **Centrale Configuratie:** Instellingen (ontvangstadres `Letsgo@grutdesigners.nl`, databasepad) staan centraal in een configuratiebestand en worden nooit hardcoded in verwerkingsscripts geplaatst.
- **Geheime Bot-Protectie:** Altijd een onzichtbaar honeypot-veld en minimale submit-tijd controle (silent fail voor bots).
- **Data Fallback:** Elke inzending wordt weggeschreven naar de lokale SQLite-database voordat de maildispatch plaatsvindt.

---

## Epic 1: Server & Runtime Environment Setup (PHP & SQLite)

**Doel & Visie**
Het inrichten en configureren van een moderne, veilige PHP 8.x runtime op de hosting/server inclusief de benodigde extensies voor SQLite, JSON-verwerking en image manipulation, zodat de applicatie en API-endpoints direct out-of-the-box functioneren.

### Taken / User Stories
- [ ] **Story 1.1: PHP Runtime & Module Activatie**
  - *Als:* Developer / Site-eigenaar
  - *Wil ik:* Een actuele PHP runtime (PHP 8.2 of 8.3) actief hebben op de serveromgeving,
  - *Zodat:* Moderne PHP-functies, types en server-side rendering foutloos werken.
  - *Taken:*
    - Activeer PHP 8.2/8.3 via het hosting control panel (of CLI).
    - Schakel de vereiste core extensies in: `pdo`, `pdo_sqlite`, `sqlite3`, `curl`, `mbstring`, `json`, `gd` (of `imagick`).
    - Configureer veilige basisinstellingen in `php.ini` (`memory_limit = 256M`, `upload_max_filesize = 10M`, `post_max_size = 12M`, `display_errors = Off` in productie).
  - *Acceptatiecriteria:* Een tijdelijk testbestand (`phpinfo()`) bevestigt dat PHP 8.x en `pdo_sqlite` actief zijn.

- [ ] **Story 1.2: Webserver Routing & Beveiligingsregels (.htaccess / Nginx)**
  - *Als:* Developer
  - *Wil ik:* Correcte webserver rewrite- en security-regels configureren,
  - *Zodat:* API-routes (`/api/...`) en pagina's netjes routeren en gevoelige opslagmappen niet direct via de browser gedownload kunnen worden.
  - *Taken:*
    - Richt `.htaccess` (bij Apache/LiteSpeed) in voor schone URL-routing.
    - [x] Blokkeer directe publieke browser-toegang tot de map `/storage` via `Deny from all` *(Reeds uitgevoerd tijdens bouw contactmodule)*.
    - Zorg dat PHP-bestanden in de `/api/` map correct worden uitgevoerd en JSON headers terugsturen.
  - *Acceptatiecriteria:* Het direct opvragen van `/storage/leads.sqlite` in de browser geeft een 403 Forbidden of 404 Not Found.

- [ ] **Story 1.3: Schrijfrechten & Storage Mappenstructuur**
  - *Als:* Serverbeheerder
  - *Wil ik:* De benodigde datamappen aanmaken met de juiste bestandpermissies,
  - *Zodat:* PHP probleemloos SQLite-databases en logbestanden kan aanmaken en updaten.
  - *Taken:*
    - Maak de mappenstructuur aan: `/storage/media`, `/storage/exports` (de map `/storage` is al actief).
    - Stel de juiste lees- en schrijfrechten in (bijv. `chmod 755` of `775`).
  - *Acceptatiecriteria:* Een PHP-testscript kan een bestand aanmaken en bewerken binnen `/storage/`.

---

## Epic 2: Lokale Ontwikkelomgeving & Database Initialisatie

**Doel & Visie**
Het opzetten van een lokale ontwikkelomgeving en een geautomatiseerd initialisatiescript dat de SQLite database-bestanden en tabellen automatisch aanmaakt bij de allereerste start.

### Taken / User Stories
- [ ] **Story 2.1: Lokale PHP Dev Server Setup**
  - *Als:* Developer / Antigravity Agent
  - *Wil ik:* Lokaal de website met PHP kunnen draaien zonder zware software te hoeven installeren,
  - *Zodat:* We wijzigingen direct interactief kunnen testen op localhost.
  - *Taken:*
    - Richt een lokaal startscript (`start.sh` of via een alias) in voor de ingebouwde PHP webserver: `php -S localhost:8000 -t .`
    - Documenteer in `README.md` hoe jij en je compagnon lokaal de PHP-server met één commando starten.
  - *Acceptatiecriteria:* De testpagina is lokaal bereikbaar via `http://localhost:8000`.

- [ ] **Story 2.2: Geautomatiseerde Database Bootstrap (Zero-Config DB Init)**
  - *Als:* Developer
  - *Wil ik:* Een bootstrap script dat controleert of de SQLite-bestanden en tabellen bestaan en deze anders direct zelf genereert,
  - *Zodat:* Er nooit handmatig een database geïmporteerd of geïnstalleerd hoeft te worden op een nieuwe omgeving.
  - *Taken:*
    - Breid de bestaande `includes/db_helper.php` uit (of maak een specifieke config) voor connecties naar zowel `storage/leads.sqlite` als `storage/content.sqlite`.
    - Zorg dat tabellen automatisch gegenereerd worden *(Deels al ingericht voor de submissions tabel)*.
    - Voeg een `init-db.php` CLI-script toe om de databases met één commando handmatig te kunnen resetten of seeden.
  - *Acceptatiecriteria:* Zodra het project voor het eerst draait, zijn alle SQLite databases automatisch aangemaakt en klaar voor gebruik.

---

## Epic 3: Modulaire Contact- & Lead-Intake Module (MVP) [DONE]

**Doel & Werking**
Een lichte, modulaire contact- en lead-intakemodule in PHP en SQLite zonder CMS-backend. De module maakt het mogelijk om via prompts in Antigravity herbruikbare CTA-contentblokken en contactformulieren aan te maken en overal op de website (in pagina’s, secties of overlays/modals) in te laden met een eenvoudige template-helper.

Aanvragen worden asynchroon (AJAX) verwerkt, direct mét paginacontext doorgemaild naar `Letsgo@grutdesigners.nl`, en veilig gelogd in een lokale SQLite-database als betrouwbare fallback.

### Technische Opzet (Documentatie)
- **Formulier API:** De endpoint `/api/submit-contact.php` vangt POST requests op. Bevat vergevingsgezinde verwerking (o.a. `?? ''` voor null-veiligheid in PHP 8.1+), time-based en honeypot bot-protectie.
- **SQLite Tabel (`submissions`):**
  - `id` (INTEGER, PK)
  - `preset_id` (TEXT): Identificeert de bron/variant (bijv. `prototype-sprint`).
  - `source_url` (TEXT): Pagina vanaf waar ingezonden is.
  - `name` (TEXT), `email` (TEXT), `phone` (TEXT), `company` (TEXT), `service_type` (TEXT), `budget` (TEXT), `message` (TEXT).
  - `payload_json` (TEXT): Volledige JSON dump van alle overige dynamische (POST) velden.
  - `created_at` (DATETIME): Tijdstempel.
- **Performance:** Database-verbinding in `includes/db_helper.php` maakt gebruik van `PRAGMA journal_mode = WAL;` en `PRAGMA synchronous = NORMAL;` voor snelle IO bij high concurrency.

### Taken / User Stories
- [x] **Story 1: Centrale Configuratie & SQLite Storage Opzet**
  - *Als:* Developer / Site-eigenaar
  - *Wil ik:* Een centrale configuratie en een beveiligde lokale SQLite-database,
  - *Zodat:* Instellingen overzichtelijk op één plek staan en inkomende leads betrouwbaar worden opgeslagen.
  - *Taken:*
    - Maak `config/contact.php` aan met centrale variabelen: ontvanger (`Letsgo@grutdesigners.nl`), databasepad (`storage/leads.sqlite`) en debug-modus.
    - Maak de map `storage/` aan met een `.htaccess`-bestand (Deny from all) ter beveiliging tegen directe downloads via de browser.
    - Schrijf een database helper/migratiebestand dat de tabel `submissions` initieert met de kolommen: `id`, `preset_id`, `source_url`, `name`, `email`, `phone`, `company`, `payload_json`, `created_at`.
  - *Acceptatiecriteria:*
    - Configuratie is via PHP oproepbaar.
    - SQLite database wordt automatisch aangemaakt met correcte permissies en tabelstructuur bij de eerste aanroep.

- [x] **Story 2: Form Renderer & Template Helper (PHP)**
  - *Als:* Frontend beheerder
  - *Wil ik:* Via de PHP-functie `render_cta_block($preset_id, $config)` dynamische formulierblokken kunnen plaatsen,
  - *Zodat:* Ik per pagina en overlay snel formulieren kan hergebruiken met contextuele teksten en de juiste veldvolgorde.
  - *Taken:*
    - Bouw de helperfunctie `render_cta_block()` in PHP.
    - Implementeer twee basispresets:
      - `quick-connect`: Naam, E-mail, Bericht.
      - `project-intake`: Naam, Bedrijf, E-mail, Telefoon, Type Dienst (pills/radio: UX/UI Design, Webdevelopment, Design System, Consultancy), Budgetindicatie (dropdown/radio), Bericht.
    - Ondersteun overrides via de `$config`-array voor: `title`, `subtitle`, `button_text` en optionele `field_order`.
    - Genereer semantische HTML5-velden met zichtbare labels, mobiele inputmodes (`type="email"`, `type="tel"`), touch targets (min. 44×44px) en een verborgen honeypot-veld (`name="website_hp"`).
  - *Acceptatiecriteria:*
    - Helper rendert geldige markup die naadloos aansluit op het bestaande grid en de design tokens.
    - Alle invoervelden hebben zichtbare labels en correcte autocomplete-attributen.

- [x] **Story 3: Client-Side AJAX Controller & UX States (JavaScript)**
  - *Als:* Bezoeker
  - *Wil ik:* Het formulier direct zonder paginaverversing kunnen versturen met heldere feedback,
  - *Zodat:* Het proces soepel verloopt en ik meteen weet of mijn bericht goed is aangekomen.
  - *Taken:*
    - Schrijf een universele JS submit handler die luistert naar submits van `[data-contact-form]`.
    - Voeg inline validatie toe bij blur en submit (verplichte velden, e-mail syntax).
    - Implementeer UI states:
      - Loading: Knop toont laadstatus/spinner en wordt direct disabled.
      - Succes: Formulier animeert soepel naar een inline bedankmelding (`aria-live="polite"`).
      - Fout: Duidelijke, veldspecifieke foutmeldingen onder de betreffende inputs.
    - Verstuur de payload via `fetch()` als POST naar `/api/submit-contact.php` inclusief browser-timestamp en bron-URL (`window.location.href`).
  - *Acceptatiecriteria:*
    - Geen paginaherlaad bij submit.
    - Voorkomt dubbele submits.
    - Voldoet aan alle gestelde UX-principes (geen layout shifts, toetsenbordvriendelijk).

- [x] **Story 4: Overlay & Modal Integratie**
  - *Als:* Bezoeker en designer
  - *Wil ik:* CTA-blokken ook in interactieve overlays kunnen openen en bedienen,
  - *Zodat:* Snelle contactmomenten laagdrempelig getoond kunnen worden zonder dat de gebruikerservaring hapert.
  - *Taken:*
    - Bouw een herbruikbare overlay-wrapper waarin `render_cta_block()` kan worden ingeladen.
    - Voeg keyboard listeners toe: sluiten op Escape.
    - Implementeer een focus-trap (blijft binnen de overlay bij doortabben) en activeer body scroll lock bij geopende overlay.
    - Desktop autofocus op het eerste veld bij openen; mobiel geen autofocus.
  - *Acceptatiecriteria:*
    - Overlay opent en sluit soepel conform de A11y-basisregels.
    - Formulier functioneert binnen de overlay identiek aan de embedded paginavariant.

- [x] **Story 5: Server-Side API Handler, Bot Defense & E-mail Dispatch (PHP)**
  - *Als:* Grut Designers team
  - *Wil ik:* Inkomende aanvragen gevalideerd ontvangen op `Letsgo@grutdesigners.nl` met paginacontext,
  - *Zodat:* We leads direct vanuit onze inbox kunnen beantwoorden met alle relevante projectinformatie.
  - *Taken:*
    - Bouw `/api/submit-contact.php` (alleen POST toegestaan).
    - Voer server-side input sanitization en validatie uit (`filter_var`, `htmlspecialchars`, telefoon-normalisatie).
    - Beveiliging: controleer honeypot-veld (stille fail bij bots) en submit-tijd (minimaal 2 seconden tussen laden en verzenden).
    - Sla de gevalideerde lead op in `storage/leads.sqlite`.
    - Stel een notificatiemail op in Grut Designers huisstijl met daarin:
      - Bronpagina (`source_url` / referrer) en gebruikte preset.
      - Alle ingevulde gegevens overzichtelijk gegroepeerd.
    - Header `Reply-To` ingesteld op het e-mailadres van de indiener.
    - Verstuur de mail naar `Letsgo@grutdesigners.nl` en retourneer een consistente JSON-respons (`{ "success": true, "message": "..." }`).
  - *Acceptatiecriteria:*
    - Mail komt direct binnen in de inbox met werkende Reply-To.
    - Lead staat correct geregistreerd in de SQLite-database.
    - Bots worden geruisloos afgevangen zonder foutmeldingen naar de client.

- [x] **Story 6: Contactformulieren & Overlay Testomgeving met Dummy Data**
  - *Als:* Reviewer / Compagnon
  - *Wil ik:* Alle varianten van contactformulieren (`quick-connect`, `project-intake`, overlays) direct op de testpagina kunnen invullen en testen,
  - *Zodat:* Ik alle UI-states (loading, inline validatie, foutmeldingen, succesberichten) en maildispatch live kan ervaren.
  - *Taken:*
    - Plaats op de testpagina zowel de standaard `quick-connect` als de uitgebreide `project-intake` met realistische dummy copy.
    - Voeg triggerknoppen toe om de contact-overlays/modals interactief te openen.
    - Voeg een knop "Vul dummy data in" toe (via een licht testscript) om met één klik een realistisch testbericht in te schieten.
    - Toon onder het formulier een live debug-paneel met de laatst verzonden JSON-payload en de meest recente SQLite log entry.
  - *Acceptatiecriteria:*
    - Alle formulier-states (leeg, ongeldig, verzendend, succes) zijn direct testbaar.
    - Zowel embedded blokken als overlays kunnen worden geactiveerd en gecontroleerd.

---

## Epic 4: Centrale Beeldbank & Media Hub [DONE]

**1. Doel & Visie**
Een centrale, gedeelde mediabron (cloud-based object storage of gedeelde media-directory) gekoppeld aan een lichte asset-catalogus. In plaats van te leunen op willekeurige lokale bestanden op individuele computers, haalt Antigravity afbeeldingen rechtstreeks uit deze centrale beeldbank op, kan de agent bestanden transformeren (bijv. WebP-conversie, resizen) en nieuwe assets publiceren of verwijderen via prompts.

**2. Hoe het Systeem Werkt**
- **Centrale Opslag (De Media Bucket / Directory):**
  - Alle geoptimaliseerde beelden en assets staan op één centrale, cloud-toegankelijke plek (bijv. Cloudflare R2 / AWS S3 of een vaste media-omgeving).
  - Elke asset is publiek of semi-publiek bereikbaar via een vast CDN/URL-patroon (bijvoorbeeld `https://media.grutdesigners.nl/cases/daklab-hero.webp` of `https://assets.grutdesigners.nl/`).
- **Asset Index & Catalogus (`assets.json` of SQLite):**
  - Een centrale index houdt de metadata bij per afbeelding:
    - `id` / `filename`: Unieke naam (bijv. `cases/daklab/hero.webp`).
    - `title` & `alt_text`: Standaard beschrijving en alt-tekst voor SEO en toegankelijkheid.
    - `dimensions`: Originele breedte en hoogte (ter voorkoming van CLS).
    - `tags` / `category`: Categorieën zoals `cases`, `team`, `ui-components`, `icons`, `backgrounds`.
    - `variants`: Beschikbare formaten (bijv. thumb, desktop, mobile).
- **Interactie & Bewerking door Antigravity:**
  - **Zoeken & Ophalen:** Antigravity kan de catalogus doorzoeken op basis van tags of context.
  - **Bewerken & Optimaliseren:** Antigravity kan assets scriptmatig schalen, croppen, converteren naar `.webp`/.avif of responsive `srcset`-varianten genereren via een lichte CLI/PHP-mediatool.
  - **Toevoegen & Verwijderen:** Nieuwe beelden worden aan de centrale opslag toegevoegd en automatisch geïndexeerd in de catalogus.
- **Frontend Integratie (PHP Media Helper):**
  - Op pagina’s en in CTA-blokken worden beelden ingeladen via een universele helper:
    ```php
    <?php render_image('cases/daklab-hero', [
        'alt'     => 'Daklab design system interface',
        'class'   => 'hero-media-cover',
        'loading' => 'lazy',
        'sizes'   => '(max-width: 768px) 100vw, 1200px'
    ]); ?>
    ```
  - De helper rendert automatisch een semantische `<picture>` of `<img>`-tag met de juiste CDN-URL's, width/height-attributen en srcset.

**3. Basisregels & Richtlijnen voor Media**
- **Bestandsformaten:** Standaard `.webp` en `.svg` (voor iconen/illustraties). Geen ongecomprimeerde `.png` of `.jpg` groter dan 500KB in productie.
- **Geen Layout Shifts (CLS):** Elke gerenderde afbeelding heeft expliciete `width` en `height` ratio's in de HTML.
- **Nomenclatuur (Bestandsnamen):** Kebab-case en contextueel (bijv. `cases-daklab-dashboard-preview.webp`).
- **Lazy Loading:** Alle beelden buiten de initiële viewport hebben standaard `loading="lazy"` en `decoding="async"`.

**4. Voorbeeld Prompts voor Antigravity**
- "Lees de beeldbank-index en plaats de 3 meest recente case-afbeeldingen van categorie design-systems in het nieuwe grid op de homepage."
- "Optimaliseer de nieuw toegevoegde afbeelding cases/brand-identity.png: converteer naar WebP, maak een desktop (1600px) en mobile (800px) variant, en werk assets.json bij."

### Taken / User Stories
- [x] **Story 1: Beeldbank Storage Structuur & Catalogus Index**
- [x] **Story 2: Responsive Image Helper (`render_image` in PHP)**
- [x] **Story 3: Asset Verwerking & Optimalisatie Tooling (CLI/Script)**
- [x] **Story 4: Asset Management & Verwijderlogica via Antigravity**

---

## Epic 5: Gecentraliseerde Content Database & Dynamic Content Engine (Current Sprint)

**1. Doel & Visie**
Het volledig loskoppelen van redactionele content van de codebase. Alle teksten (kopteksten, body copy, CTA-labels, case-beschrijvingen en micro-copy) worden opgeslagen in een centrale, relationele content-database (SQLite). Hierdoor kunnen meerdere redacteuren en prompts in Antigravity gelijktijdig copy aanpassen, nieuwe vertalingen toevoegen (meertaligheid) en dynamische contentvariaties (A/B-tests, contextuele copy per doelgroep of campagne) uitserveren zonder dat HTML/PHP-templatebestanden aangepast hoeven te worden.

**2. Hoe het Systeem Werkt**
- **1. Content Datamodel (SQLite: `storage/content.sqlite`):** De database is opgebouwd rond een modulair key-value en blokken-schema:
  - `pages`: Beheert paginametadata (slug, template, status, SEO title, meta description).
  - `content_keys`: Unieke identifiers voor vaste tekstfragmenten (bijv. `global.header.cta_btn`, `cases.daklab.hero_title`, `footer.tagline`).
  - `content_translations`: De daadwerkelijke copy per taal en sleutel (`key_id`, `locale` [nl, en, fy], `value`, `updated_at`, `version`).
  - `content_variants` (A/B & Dynamische Tests): Optionele alternatieve copy per sleutel (`key_id`, `variant_key` [bijv. variant_b_korte_pitch], `weight_percentage`, `active`).
- **2. Ophalen & Injectie in Templates (PHP Helpers):** In plaats van hardcoded tekst in PHP/HTML-bestanden worden helpers gebruikt:
  - *Eenvoudig tekstfragment:* `<h1><?= t('cases.daklab.hero_title', 'Standaard fallback titel'); ?></h1>`
  - *Rijkere Markdown/HTML contentsectie:* `<div class="prose"><?= content_block('cases.daklab.intro_story'); ?></div>`
  - *Dynamische variant (A/B testing):* `<button class="btn btn-primary"><?= t_dynamic('home.hero.cta_button', ['default' => 'Start een project', 'test_id' => 'hero_cta_v1']); ?></button>`
- **3. Meertaligheid (Multi-Language Routing):**
  - De taal (locale) wordt automatisch bepaald op basis van de URL (`/`, `/en/`, `/fy/`), cookie of HTTP-header.
  - De PHP-engine laadt in één geoptimaliseerde query alle content-keys voor de actieve pagina en taal in het geheugen (in-memory caching).
  - Ontbreekt een vertaling in een doeltaal? Dan valt het systeem geruisloos terug op de standaardtaal (`nl`) of de meegegeven fallback-tekst.
- **4. Redactie & Antigravity Workflow:**
  - *Teksten Aanpassen:* Prompts in Antigravity kunnen rechtstreeks SQL-updates uitvoeren of een exportbestand bijwerken ("Vervang de introtekst van de case Daklab in het Nederlands en vertaal deze direct naar het Engels in de database").
  - *Bulk Import/Export:* Mogelijkheid om content als JSON/CSV te exporteren en te importeren, zodat externe redacteuren in Google Sheets / Drive teksten kunnen aanleveren of redigeren.
  - *Versiebeheer & Audit Trail:* Elke wijziging bewaart een timestamp en vorige versie, zodat tekstwijzigingen eenvoudig kunnen worden teruggedraaid.

**3. Basisregels & Kaders voor Content Management**
- **Zero-Template Hardcoding:** Geen statische publieke teksten meer direct in `.php`-viewbestanden.
- **Performance & Caching:** De content-engine gebruikt in-memory array-caching (of APCu / static JSON cache). Er vindt maximaal één lichte SQLite-query plaats per page-render.
- **Markdown Support:** Vrije tekstvelden ondersteunen Markdown-notatie voor vetgedrukt, schuingedrukt, lijsten en links, die veilig worden omgezet naar semantische HTML.
- **XSS & Veiligheid:** Dynamische tekstoutputs worden standaard veilig gesanitized (htmlspecialchars voor platte tekst, whitelist HTML-parser voor Markdown).

**4. Voorbeeld Prompts voor Antigravity**
- "Voeg voor alle knoppen op de dienstenpagina een Engelse en Friese vertaling toe aan de content-database."
- "Richt een A/B-testvariant in voor de sleutel `home.hero.title`: Variant A blijft de huidige tekst, Variant B wordt 'Wij ontwerpen digitale producten die impact maken'. Activeer 50/50 distributie."

### Taken / User Stories
- [x] **Story 1: Database & Datamodel Opzet (`storage/content.sqlite`)**
  - *Scope:* Aanmaken van de database met de tabellen `pages`, `content_keys`, `content_translations` en `content_variants`.
- [x] **Story 2: PHP Content Helpers (`t()`, `content_block()`, `t_dynamic()`)**
  - *Scope:* Bouwen van de helperfuncties voor efficiënte caching en weergave van teksten en markdown, met fallback en sanitization.
- [ ] **Story 3: Content Migratie & Implementatie in Templates**
  - *Scope:* Vervang alle hardcoded teksten in `test/prototype-sprint.php` en de uiteindelijke `index.php` door de `t()` en `content_block()` helpers. Vul tegelijkertijd de `content.sqlite` database met de initiële Nederlandse teksten (bijv. via een seed-script).
- [ ] **Story 4: Meertaligheid (Routing & URL Structuur)**
  - *Scope:* Implementeer basis-routing zodat de website kan schakelen tussen talen (bijv. `/nl/` en `/en/`) en zorg dat de ContentEngine automatisch de juiste taal inlaadt op basis van de opgevraagde URL.


---

## Epic: Bugs & Losse Taken (Onderhoud)

**Doel & Werking**
Dit is de verzamelbak voor kleine aanpassingen, bugfixes en styling-tweaks die niet onder een grote feature-epic vallen, maar wel opgepakt moeten worden. We werken deze lijst stapsgewijs af.

### Taken / User Stories

- [ ] **Voorbeeld bug/taak**
  - *Scope:* (Beschrijf hier de issue of gewenste kleine aanpassing)
  - *Acceptatiecriteria:* (Wanneer is het opgelost?)
