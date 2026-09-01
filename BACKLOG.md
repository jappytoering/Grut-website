# Grut Designers - Website & Platform Backlog

Dit document dient als de centrale 'Single Source of Truth' voor de doorontwikkeling van de Grut Designers website. We hanteren een strikte Epic > Story structuur.

---

<details>
<summary><strong>Afgeronde Epics (Archief)</strong></summary>

### Epic 1: Design System, Contact Engine & Transactionele Mail [DONE]
**Doel:** Ontwikkelen van interactieve contactformulieren met real-time validatie, opslag in SQLite en automatische transactional emails (beheerder & klantbevestiging). Inclusief anti-spam honeypot.

### Epic 2: Drag & Drop Form Builder & Dynamische Koppeling [DONE]
**Doel:** Visuele form-editor in de admin-omgeving (`admin/forms.php`) voor het drag-and-drop samenstellen van de velden en het dynamisch renderen van het formulier op de frontend (`form_helper.php`).

### Epic 3: Centrale Beeldbank & Media Hub [DONE]
**Doel & Visie**
Een beheer-interface bouwen in de admin (`admin/media.php`) voor het uploaden, schalen (WEBP conversie), taggen en selecteren van beelden, zodat pagina-componenten gemakkelijk en visueel van media voorzien kunnen worden.

### Epic 4: Admin Navigatie & Menubeheer [DONE]
**Doel:** Introductie van een robuuste admin UI-navigatie (Pagina's, Componenten, Formulieren, Media, Menus) met een beheer-interface voor de sitewide navigatie-menu's en dynamische frontend-rendering via `render_menu()`.

### Epic 5: Template-Gebonden Pagina Engine & Componenten Builder [DONE]
**Doel:** CMS functionaliteit creëren. De backend heeft nu een drag-and-drop page builder gekregen met per blok instelbare eigenschappen (`blockSchemas`). De frontend maakt gebruik van `page-shell.php` om de basis lay-out (Header, Footer, SEO tags) te behouden en laadt de page blocks daartussen. Inclusief live/draft status.

### Epic 6: Multi-User Collaboration & RBAC (Role Based Access Control) [DONE]
**Doel & Visie**
Naarmate Grut groeit, is het belangrijk dat de content beheerd kan worden zonder direct admin/developer-toegang tot de server en broncode (Git).

</details>

---

## Actieve Roadmap (Toekomstige Sprints)

### Epic 7: Bugfixing & Kwaliteitsgarantie Admin
**Doel & Visie**
Oplossen van openstaande kritieke en niet-kritieke bugs in de backend na de recente structuur-wijzigingen en migraties naar de testomgeving.

**Taken / User Stories**
- [x] **Story 1: Testomgeving Routing Herstellen** - Het 404 & Fatal error probleem op subpagina's oplossen door aanpassingen in `.htaccess`, `index.php` (engine fallback) en toevoegen van vergeten afhankelijkheden (`form_helper.php`).
- [x] **Story 2: Testomgeving Beveiliging** - Toevoegen van `noindex, nofollow` meta-tags en HTTP Headers via `.htaccess` zodat test.grutdesigners.nl niet geïndexeerd wordt door Google of AI bots.
- [x] **Story 3: Admin Formulieren Menu** - Het formulieren menu-item of navigatiestructuur toevoegen zodat specifieke formulieren (bijv. contact-formulier) weer individueel bereikbaar en bewerkbaar zijn.
- [x] **Story 4: Pagina Opslaan Probleem** - Onderzoeken en oplossen van het probleem waardoor bewerkingen of nieuwe toevoegingen in de admin niet correct in de database (SQLite) worden opgeslagen.
- [x] **Story 5: Preview Functionaliteit** - Herstellen van de 'Preview pagina' functionaliteit in de editor (zodat concepten zichtbaar zijn zonder live te staan). 
