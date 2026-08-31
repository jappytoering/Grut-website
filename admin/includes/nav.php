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

    <a href="forms.php" class="nav-item <?= ($current_page == 'forms.php' || $current_page == 'form_editor.php') ? 'active' : '' ?>">
        Formulieren
    </a>
    
    <a href="media.php" class="nav-item <?= $current_page == 'media.php' ? 'active' : '' ?>">
        Media Hub
    </a>
    
    <a href="menus.php" class="nav-item <?= $current_page == 'menus.php' ? 'active' : '' ?>">
        Inhoud menu's
    </a>
    
    <a href="content.php" class="nav-item <?= $current_page == 'content.php' ? 'active' : '' ?>" style="margin-top: 2rem; opacity: 0.7;">
        Vertaal Sleutels (Legacy)
    </a>
</nav>
