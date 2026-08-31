# Grut Designers - Website & Platform Backlog

Dit document dient als de centrale 'Single Source of Truth' voor de doorontwikkeling van de Grut Designers website. We hanteren een strikte Epic > Story structuur.

---

<details>
<summary><strong>Afgeronde Epics (Archief)</strong></summary>

### Epic 1: Design System, Contact Engine & Transactionele Mail [DONE]
**Doel:** Ontwikkelen van interactieve contactformulieren met real-time validatie, opslag in SQLite en automatische transactional emails (beheerder & klantbevestiging). Inclusief anti-spam honeypot.

### Epic 2: Drag & Drop Form Builder & Dynamische Koppeling [DONE]
**Doel:** Visuele form-editor in de admin-omgeving (`admin/forms.php`) voor het drag-and-drop samenstellen van de velden en het dynamisch renderen van het formulier op de frontend (`form_helper.php`).

### Epic 4: Admin Navigatie & Menubeheer [DONE]
**Doel:** Introductie van een robuuste admin UI-navigatie (Pagina's, Componenten, Formulieren, Media, Menus) met een beheer-interface voor de sitewide navigatie-menu's en dynamische frontend-rendering via `render_menu()`.

### Epic 5: Template-Gebonden Pagina Engine & Componenten Builder [DONE]
**Doel:** CMS functionaliteit creëren. De backend heeft nu een drag-and-drop page builder gekregen met per blok instelbare eigenschappen (`blockSchemas`). De frontend maakt gebruik van `page-shell.php` om de basis lay-out (Header, Footer, SEO tags) te behouden en laadt de page blocks daartussen. Inclusief live/draft status.

</details>

---

## Actieve Roadmap (Toekomstige Sprints)

### Epic 3: Centrale Beeldbank & Media Hub
**Doel & Visie**
Een beheer-interface bouwen in de admin (`admin/media.php`) voor het uploaden, schalen (WEBP conversie), taggen en selecteren van beelden, zodat pagina-componenten gemakkelijk en visueel van media voorzien kunnen worden.

**Taken / User Stories**
- [x] **Story 1: Upload & Compressie Engine** - Image upload afhandelen, EXIF strippen en auto-converteren naar `.webp`.
- [ ] **Story 2: Admin Media Hub** - Grid weergave, verwijderen, pad-kopieer-acties en tag-beheer.
- [x] **Story 3: Pagina-Builder Integratie** - In de Page Builder (`blockSchemas`) een 'image-picker' component bouwen die opent in een modal.
- [x] **Story 4: Responsive Image Helper** - `render_image()` functie in PHP die automatisch `srcset` genereert voor optimale performance (Lighthouse).

---

### Epic 6: Multi-User Collaboration & RBAC (Role Based Access Control)
**Doel & Visie**
Naarmate Grut groeit, is het belangrijk dat de content beheerd kan worden zonder direct admin/developer-toegang tot de server en broncode (Git).

**Taken / User Stories**
- [ ] **Story 1: User Rollen & Authenticatie** - SQLite tabel `users` uitbreiden/aanmaken met rollen: `SuperAdmin` (kan alles, inclusief developer tools) en `Redacteur` (kan alleen CMS en Media benaderen).
- [ ] **Story 2: UI Permissies** - Verberg irrelevante menu-items voor redacteuren (zoals Database resets of Code-weergaven).
- [ ] **Story 3: Git Sync Workflow** - (Optioneel) Een 'Deploy' knop voor SuperAdmins waarmee eventueel gewijzigde file-storage structuur (`storage/`) via een background worker op de live server veilig gesynct kan worden. 
