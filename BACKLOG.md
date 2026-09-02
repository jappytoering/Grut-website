# Grut Designers - Website & Platform Backlog

Dit document dient als de centrale 'Single Source of Truth' voor de doorontwikkeling van de Grut Designers website. We hanteren een strikte Epic > Feature > Story structuur.

---

<details>
<summary><strong>Afgeronde Epics (Archief MVP)</strong></summary>

### Epic 1: Design System, Contact Engine & Transactionele Mail [DONE]
### Epic 2: Drag & Drop Form Builder & Dynamische Koppeling [DONE] *(Fase 1: JSON gebaseerd)*
### Epic 3: Centrale Beeldbank & Media Hub [DONE] *(Fase 1)*
### Epic 4: Admin Navigatie & Menubeheer [DONE] *(Fase 1: JSON gebaseerd)*
### Epic 5: Template-Gebonden Pagina Engine & Componenten Builder [DONE] *(Fase 1)*
### Epic 6: Multi-User Collaboration & RBAC (Role Based Access Control) [DONE]
### Epic 7: Bugfixing & Kwaliteitsgarantie Admin [DONE]
*(O.a. routing herstel, beveiliging testomgeving, caching problemen, admin bugs).*

</details>

---

## Architectuur & Randvoorwaarden

1. **Homepage Uitzondering**: De homepage (index) heeft een vaste, complexe structuur en wordt expliciet NIET via de dynamische page builder beheerd.
2. **Database is Source of Truth**: Alle beheersbare content (Pagina's, Componenten, Formulieren, Menu's) moet uiteindelijk uit SQLite komen, niet uit `.json` bestanden of hardcoded HTML.
3. **Scheiding van verantwoordelijkheden**:
   - **Template**: Bepaalt de structuur, header/footer layout, en grid (bijv. `overlay` of `page`).
   - **Component**: Een bouwblok met vaste velden en logica (bijv. `text-block`, `image-slider`).
   - **Content**: De daadwerkelijke data (JSON payload of DB relaties) ingevoerd door de admin in een component.

---

## Actieve Roadmap

### EPIC 8: Content Management & Page Administration (Fase 2)

#### Feature 1: Page Management & Metadata
*Doel: Beheer van pagina entiteiten en hun vaste instellingen.*
- [ ] **Story 1.1: Page overview & CRUD** - Bekijken, aanmaken, bewerken en verwijderen van pagina's.
- [ ] **Story 1.2: Select page template** - Keuze tussen 'Page' of 'Overlay' template bij het aanmaken/bewerken, wat de frontend rendering bepaalt.
- [ ] **Story 1.3: Manage page metadata** - Vaste velden (Paginatitel, H1, slug, Meta title, Meta description, SEO/Social tags).
- [x] **Story 1.4: Select CTA/contact form** - Koppelen van een contactformulier (via `form_id` relatie) aan de footer van een specifieke pagina.

#### Feature 2: Content Builder
*Doel: Robuuste opslag en sortering van page blocks.*
- [ ] **Story 2.1: Add & Edit component** - Interface om specifieke componenten toe te voegen en inhoudelijk te bewerken.
- [ ] **Story 2.2: Delete component** - Veilig verwijderen van componenten van een pagina.
- [ ] **Story 2.3: Reorder components** - Sortering (drag & drop) opslaan via `sort_order` in de database.
- [ ] **Story 2.4: Save component data** - Robuuste validatie en JSON-opslag van component-specifieke attributen in `content_json`.

#### Feature 3: Component Library
*Doel: Strikte definitie en frontend/backend uitwerking per component.*
*(Status: Voor al deze componenten moeten specifieke velden, validaties en relaties nog ontworpen worden)*
- [x] **Story 3.1: Content Audit & Schema Definitie** - Prototype Sprint scan afgerond, componenten geïdentificeerd.
- [ ] **Story 3.2: Hero Propositie (`sprint_hero`)** - Titel, intro, tags
- [ ] **Story 3.3: Image Slider (`sprint_slider`)** - Slides met images, titel, subtitle
- [ ] **Story 3.4: Text Checklist (`sprint_text_checklist`)** - Titel, intro, checkboxlijst
- [ ] **Story 3.5: Meta List (`sprint_meta_list`)** - Modal meta lijst met waarden
- [ ] **Story 3.6: Highlight Block (`sprint_highlight_block`)** - Tekst, image, checklist
- [ ] **Story 3.7: Columns Block (`sprint_columns_block`)** - 2-koloms checklist
- [ ] **Story 3.8: Days List (`sprint_days_list`)** - Interactief stappenplan
- [ ] **Story 3.9: FAQ Accordion (`sprint_faq`)** - Vragen & antwoorden
- [ ] **Story 3.10: Trusted Logos (`sprint_trusted_logos`)** - Cloud met logo's
- [ ] **Story 3.11: CTA Block (`sprint_cta_block`)** - Formulier integratie
- [x] **Story 3.12: Prototype Sprint Database Seed** - Database backend setup & component parsing afgerond.

#### Feature 4: Form Builder (Database-driven) [DONE]
*Doel: Conceptuele scheiding van formulieren en hun velden in de DB (vervangt JSON).*
- [x] **Story 4.1: Database schema Formulieren** - SQLite tabellen ontwerpen voor `forms` en `form_fields`.
- [x] **Story 4.2: Create & Manage contact forms** - Formulieren aanmaken, instellingen (ontvanger, succestekst) beheren.
- [x] **Story 4.3: Manage & Assign form fields** - Velden (text, email, checkbox, etc.) aanmaken, sorteren en koppelen aan formulieren.
- [x] **Story 4.4: Dynamic Form Rendering** - Frontend forms ophalen en renderen vanuit SQLite in plaats van `forms.json`.

#### Feature 5: Media Hub Integratie
*Doel: Naadloze koppeling tussen content en de centrale beeldbank.*
- [ ] **Story 5.1: Media metadata management** - Alt-teksten en titels in de Media Hub (`media_assets`) bewerken na upload.
- [ ] **Story 5.2: Select media in component** - Een UI widget in de Content Builder om bestaande media (op basis van `asset_id`) te selecteren (i.p.v. losse uploads).
- [ ] **Story 5.3: Select and reorder multiple media** - Functionaliteit voor slider/gallery componenten.

#### Feature 6: Database-driven Website & Migratie
*Doel: Uitfaseren van dummy data en hardcoded JSON afhankelijkheden.*
- [x] **Story 6.1: Migrate forms to DB** - Bestaande formulieren uit `forms.json` migreren naar de nieuwe SQLite tabellen.
- [x] **Story 6.2: Menu's to DB** - Migratie van `menus.json` naar SQLite is voltooid.
- [ ] **Story 6.3: Migrate pages & components** - Huidige hardcoded blokken in `page_blocks` opschonen en converteren naar de strikte Component Library standaarden.
- [ ] **Story 6.4: Remove dummy data dependencies** - Code opschonen zodat fallback dummy-data in frontend en backend volledig is verdwenen.

### EPIC 9: Database Tuning & Concurrency [DONE]
- [x] **Story 9.1: Centralize DB Connection** - Implementeer `getCmsConnection()` met standaard SQLite PRAGMA's (`WAL`, `NORMAL`, `busy_timeout=5000`, `foreign_keys=ON`).
- [x] **Story 9.2: Database Indexes** - Indexen toegevoegd voor `pages(slug)` en `page_blocks(page_id, sort_order)`.

### EPIC 10: Frontend Asset & Performance Engine [DONE]
- [x] **Story 10.1: Picture & WebP/AVIF Support** - Helper `render_image()` aangepast naar `<picture>` generatie.
- [x] **Story 10.2: Server Caching & Brotli** - `.htaccess` geüpdatet met Brotli compressie, Cache-Control headers en restricties op `/storage/*.sqlite`.

### EPIC 11: Admin Safety & Guardrails [DONE]
- [x] **Story 11.1: Dirty-checking Editor** - `beforeunload` waarschuwingen in page editor en form editor.
- [x] **Story 11.2: Inline Slug-checker** - Real-time validatie van unieke slugs via `api/admin/check_slug.php`.
- [x] **Story 11.3: CSRF Protectie** - Globale CSRF-token validatie op alle mutating endpoints (POST, PUT, DELETE).


## 🐛 Gevonden Bugs & Openstaande Reparaties

### [BUG / STORY] Fix: Media Aanmaken
- **Status:** [TODO]
- **Probleem:** Test faalde tijdens de geautomatiseerde test-suite.
- **Foutmelding/Symptoom:** `SQLSTATE[HY000]: General error: 1 table media_assets has no column named file_name`
- **Betrokken bestand(en):** `/Users/jappytoering/Documents/Antigravity/Grut-website-2026/bin/smoke-test.php`
- **Acceptatiecriteria:**
  - [ ] Onderliggend probleem oplossen.
  - [ ] Test in `admin/test-suite.php` slaagt met `[PASS]`


### [BUG / STORY] Fix: Dynamic Block Renderer
- **Status:** [TODO]
- **Probleem:** Test faalde tijdens de geautomatiseerde test-suite.
- **Foutmelding/Symptoom:** `component 'faq_accordion' mist 'question' array key.`
- **Betrokken bestand(en):** `/Users/jappytoering/Documents/Antigravity/Grut-website-2026/bin/smoke-test.php`
- **Acceptatiecriteria:**
  - [ ] Onderliggend probleem oplossen.
  - [ ] Test in `admin/test-suite.php` slaagt met `[PASS]`
