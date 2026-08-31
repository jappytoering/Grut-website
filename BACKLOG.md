# Product Backlog: Grut Website 2026

Welkom bij de backlog van het Grut website project. We werken hier gestructureerd aan nieuwe functionaliteiten en verbeteringen.

## Werkregels voor samenwerking:
- We werken altijd strikt aan één story/taak tegelijk.
- Voordat je code schrijft voor een taak, stemmen we kort de aanpak af.
- Nadat een taak succesvol is gebouwd en getest, werk je de status in `BACKLOG.md` direct bij naar afgevinkt (`- [x]`).
- Raak geen bestanden of logica aan die horen bij toekomstige stories.

## Git Branch-Strategie & Workflow:
- **`main`**: Live productieomgeving. Bevat uitsluitend gereleaste, geteste code.
- **`test` / `staging`**: Testomgeving waar de klant kan reviewen.
- **Feature Branches**: Elk teamlid (of AI-agent) werkt op afzonderlijke branches (bijv. `feature/rbac` of `epic-6-admin`). Na afronding volgt een merge naar `test`.

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

## Actieve Sprints & Epics

### Epic 5: Gecentraliseerde Content Database & Dynamic Content Engine (Current Sprint)

**Doel & Visie**
Het volledig loskoppelen van redactionele content van de codebase in `storage/content.sqlite`. Alle teksten (kopteksten, body copy, CTA-labels) worden modulair beheerd. Hierdoor kunnen redacteuren en AI-prompts copy aanpassen en vertalen (meertaligheid) zonder `.php` bestanden aan te raken.

**Taken / User Stories**
- [x] **Story 1: Database & Datamodel Opzet (`storage/content.sqlite`)**
- [x] **Story 2: PHP Content Helpers (`t()`, `content_block()`, `t_dynamic()`)**
- [x] **Story 3: Content Migratie & Implementatie in Templates** (Prototype Sprint afgerond)
- [x] **Story 4: Meertaligheid (Routing & URL Structuur)**
  - *Scope:* Implementeer basis-routing zodat de website kan schakelen tussen talen (bijv. `/nl/` en `/en/`) en zorg dat de ContentEngine automatisch de juiste taal inlaadt op basis van de opgevraagde URL.

---

### Epic 6: Multi-User Collaboration & Role-Based Access Control (RBAC) (Next Sprint)

**Doel & Visie**
Om soepel samen te werken aan de website zonder dat code en content elkaar in de weg zitten, scheiden we de opslag van content/media strikt van de core codebase (Sync Workflow). Daarnaast introduceren we een afgeschermd Admin Dashboard waar content redacteuren (via RBAC) teksten en beelden kunnen beheren. Dit bouwt direct voort op de Content Database (Epic 5) en Media Hub (Epic 4).

**Taken / User Stories**
- [x] **Story 1: Storage Scheiding & Sync-Tool**
  - *Scope:* Verifieer ontkoppeling van SQLite-databases en media (via `.gitignore`). Richt een synctool/migratiescript in (`includes/sync_helper.php`) om content/media veilig te synchroniseren tussen test en productie.
- [x] **Story 2: Authenticatie & Rollenmatrix (RBAC)**
  - *Scope:* Bouw de middleware/auth-guards in `includes/auth_helper.php` (bijv. `has_permission('edit_content')`) en definieer de inloglogica en rollen `super_admin` en `editor`.
- [x] **Story 3: Content & Media Management UI (Admin Dashboard)**
  - *Scope:* Ontwikkel de afgeschermde views voor redacteuren. Maak interfaces voor het bewerken van teksten, het genereren van pagina's via blauwdrukken en het beheren van de Media Hub.

---

### Epic 1: Server & Runtime Environment Setup (PHP & SQLite)

**Doel & Visie**
Inrichten van de productie webserver (hosting) inclusief veilige PHP 8.x runtime en storage-permissies.

**Taken / User Stories**
- [ ] **Story 1.1: PHP Runtime & Module Activatie** (PDO, SQLite, GD)
- [ ] **Story 1.2: Webserver Routing & Beveiligingsregels (.htaccess / Nginx)**
- [ ] **Story 1.3: Schrijfrechten & Storage Mappenstructuur** (chmod `/storage`)

---

### Epic 2: Lokale Ontwikkelomgeving & Database Initialisatie

**Doel & Visie**
Soepele lokale dev-omgeving met automatische generatie van databases. (Grotendeels actief, nog finetunen voor README onboarding).

**Taken / User Stories**
- [ ] **Story 2.1: Lokale PHP Dev Server Setup** (`start.sh`)
- [ ] **Story 2.2: Geautomatiseerde Database Bootstrap (Zero-Config DB Init)** (`init-db.php`)

---

## Epic: Bugs & Losse Taken (Onderhoud)

- [ ] **Voorbeeld bug/taak**
  - *Scope:* (Beschrijf hier de issue of gewenste kleine aanpassing)

---

## Archief (Afgeronde Epics)

<details>
<summary>Bekijk afgeronde functionaliteiten</summary>

### Epic 3: Modulaire Contact- & Lead-Intake Module (MVP) [DONE]
- [x] Story 1: Centrale Configuratie & SQLite Storage Opzet
- [x] Story 2: Form Renderer & Template Helper (PHP)
- [x] Story 3: Client-Side AJAX Controller & UX States (JavaScript)
- [x] Story 4: Overlay & Modal Integratie
- [x] Story 5: Server-Side API Handler, Bot Defense & E-mail Dispatch (PHP)
- [x] Story 6: Contactformulieren & Overlay Testomgeving met Dummy Data

### Epic 4: Centrale Beeldbank & Media Hub [DONE]
- [x] Story 1: Beeldbank Storage Structuur & Catalogus Index
- [x] Story 2: Responsive Image Helper (`render_image` in PHP)
- [x] Story 3: Asset Verwerking & Optimalisatie Tooling (CLI/Script)
- [x] Story 4: Asset Management & Verwijderlogica via Antigravity

### Epic: Transactionele E-mailengine voor Contactformulieren (Afgerond)
- [x] Architectuur & Helper (`includes/mail_helper.php`)
- [x] E-mail 1: Admin Notificatie
- [x] E-mail 2: Bezoeker Bevestiging
- [x] API Koppeling in `api/submit-contact.php`

</details>
