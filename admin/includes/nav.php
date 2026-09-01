<?php
// Bepaal actieve pagina voor navigatie als dit nog niet is gedaan
$current_page = $current_page ?? basename($_SERVER['PHP_SELF']);

// Bepaal of de 'Pagina's' hoofdgroep actief is
$is_pages_active = in_array($current_page, ['pages.php', 'page_editor.php', 'components.php']);
?>
<nav class="sidebar-nav">
    <a href="dashboard.php" class="nav-item <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
        Dashboard
    </a>
    
    <!-- Pagina's Groep met Submenu -->
    <div class="nav-group <?= $is_pages_active ? 'is-active' : '' ?>">
        <a href="pages.php" class="nav-item nav-item-parent <?= ($current_page == 'pages.php' || $current_page == 'page_editor.php') ? 'active' : '' ?>">
            Pagina's
            <span class="nav-arrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </span>
        </a>
        <div class="nav-submenu">
            <a href="components.php" class="nav-item nav-subitem <?= $current_page == 'components.php' ? 'active' : '' ?>">
                Globale Componenten
            </a>
        </div>
    </div>

<?php
// Bepaal of Formulieren groep actief is
$is_forms_active = in_array($current_page, ['forms.php', 'form_editor.php']);
$forms_file = __DIR__ . '/../../storage/forms.json';
$nav_forms = file_exists($forms_file) ? json_decode(file_get_contents($forms_file), true) : [];
?>
    <div class="nav-group <?= $is_forms_active ? 'is-active' : '' ?>">
        <a href="forms.php" class="nav-item nav-item-parent <?= ($current_page == 'forms.php' && !isset($_GET['index']) && !isset($_GET['new'])) ? 'active' : '' ?>">
            Formulieren
            <span class="nav-arrow">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </span>
        </a>
        <div class="nav-submenu">
            <?php foreach ($nav_forms as $i => $form): ?>
                <a href="form_editor.php?index=<?= $i ?>" class="nav-item nav-subitem <?= ($current_page == 'form_editor.php' && isset($_GET['index']) && $_GET['index'] == $i) ? 'active' : '' ?>">
                    <?= htmlspecialchars($form['title'] ?? 'Naamloos') ?>
                </a>
            <?php endforeach; ?>
            <a href="form_editor.php?new=1" class="nav-item nav-subitem <?= ($current_page == 'form_editor.php' && isset($_GET['new'])) ? 'active' : '' ?>" style="color: var(--color-accent);">
                + Nieuw formulier
            </a>
        </div>
    </div>
    
    <a href="media.php" class="nav-item <?= $current_page == 'media.php' ? 'active' : '' ?>">
        Media Hub
    </a>
    
    <?php if (AuthEngine::has_role('super_admin')): ?>
        <a href="menus.php" class="nav-item <?= $current_page == 'menus.php' ? 'active' : '' ?>">
            Inhoud menu's
        </a>
        
        <a href="users.php" class="nav-item <?= $current_page == 'users.php' ? 'active' : '' ?>">
            Gebruikers
        </a>

        <a href="deploy.php" class="nav-item <?= $current_page == 'deploy.php' ? 'active' : '' ?>" style="margin-top: 1rem; color: #d97706;">
            🚀 Deploy to Test
        </a>
        
        <a href="content.php" class="nav-item <?= $current_page == 'content.php' ? 'active' : '' ?>" style="margin-top: 2rem; opacity: 0.7;">
            Vertaal Sleutels (Legacy)
        </a>
    <?php endif; ?>
</nav>
