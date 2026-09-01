<?php
require_once __DIR__ . '/../includes/menu_helper.php';
require_once __DIR__ . '/../includes/form_helper.php';
// $page and $blocks are injected by engine.php
$seo_title = !empty($page['seo_title']) ? $page['seo_title'] : 'Grut Designers | Digital Product Design';
$meta_description = !empty($page['meta_description']) ? $page['meta_description'] : 'Wij ontwerpen digitale producten die echt werken.';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
    <meta name="theme-color" content="#0E0F0F"/>
    
    <title><?= htmlspecialchars($seo_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description) ?>"/>
    
    <?php 
    $is_test_env = ($_SERVER['HTTP_HOST'] === 'test.grutdesigners.nl' || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
    if ($is_test_env): 
    ?>
    <!-- Blokkeer Google en AI op de testomgeving -->
    <meta name="robots" content="noindex, nofollow"/>
    <meta name="googlebot" content="noindex, nofollow"/>
    <?php endif; ?>
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= htmlspecialchars($seo_title) ?>"/>
    <meta property="og:description" content="<?= htmlspecialchars($meta_description) ?>"/>
    <meta property="og:url" content="https://grutdesigners.nl/<?= htmlspecialchars($page['slug'] ?? '') ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="https://grutdesigners.nl/assets/og-preview.jpg?v=4"/>
    
    <link rel="icon" href="/assets/logo-beeldmerk.svg" type="image/svg+xml"/>
    
    <!-- Preload Fonts -->
    <link rel="preload" href="/assets/fonts/ClashGrotesk-400.woff2" as="font" type="font/woff2" crossorigin/>
    <link rel="preload" href="/assets/fonts/ClashGrotesk-500.woff2" as="font" type="font/woff2" crossorigin/>
    <link rel="preload" href="/assets/fonts/ClashGrotesk-700.woff2" as="font" type="font/woff2" crossorigin/>
    <link rel="preload" href="/assets/fonts/Satoshi-400.woff2" as="font" type="font/woff2" crossorigin/>
    
    <link href="/style-v43.min.css" rel="stylesheet"/>
    <style>
        /* Font-faces */
        @font-face { font-family: "Clash Grotesk"; src: url("/assets/fonts/ClashGrotesk-400.woff2") format("woff2"); font-weight: 400; font-display: swap; }
        @font-face { font-family: "Clash Grotesk"; src: url("/assets/fonts/ClashGrotesk-500.woff2") format("woff2"); font-weight: 500; font-display: swap; }
        @font-face { font-family: "Clash Grotesk"; src: url("/assets/fonts/ClashGrotesk-700.woff2") format("woff2"); font-weight: 700; font-display: swap; }
        @font-face { font-family: "Satoshi"; src: url("/assets/fonts/Satoshi-400.woff2") format("woff2"); font-weight: 400; font-display: swap; }
    </style>
</head>
<body class="page-<?= htmlspecialchars($page['slug'] ?? 'home') ?>">

    <!-- Draft Preview Banner -->
    <?php if (($page['status'] ?? '') === 'draft'): ?>
    <div style="background: var(--color-yellow); color: #000; text-align: center; padding: 0.5rem; font-weight: 600; font-size: 0.85rem; position: sticky; top: 0; z-index: 10000;">
        ⚠️ Je bekijkt een Concept versie. Deze pagina is onzichtbaar voor bezoekers.
    </div>
    <?php endif; ?>

    <?php 
    // Controleer of we op een overlay-achtige pagina zitten waar nav en footer verborgen moeten zijn
    $is_overlay_page = (!empty($page['template']) && $page['template'] === 'overlay') || 
                       (isset($page['slug']) && (strpos($page['slug'], 'prototype-sprint') !== false || strpos($page['slug'], 'overlay-') === 0));
    ?>

    <?php if (!$is_overlay_page): ?>
    <!-- Navigation -->
    <nav class="nav" id="nav">
        <div class="nav-container">
            <a aria-label="Grut Designers Home" class="nav-logo" href="/">
                <img alt="Grut Designers Logo" height="38" src="/assets/logo-beeldmerk.svg" width="38" class="header-logo"/>
            </a>
            
            <div class="nav-links">
                <!-- Dynamisch Hoofdmenu -->
                <?= render_menu('main', 'nav-link') ?>
            </div>
            
            <div class="nav-actions hide-on-mobile">
                <a class="btn btn-outline" href="mailto:letsgo@grutdesigners.nl">
                    <svg fill="none" height="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="width: 20px; height: 20px; margin-right: 8px;" viewBox="0 0 24 24" width="20"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Mail ons
                </a>
                <a class="btn" href="tel:+31620869929">
                    <svg fill="none" height="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="width: 20px; height: 20px; margin-right: 8px;" viewBox="0 0 24 24" width="20"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    Bel ons
                </a>
            </div>
            
            <button aria-label="Menu openen" class="nav-toggle" id="nav-toggle">
                <span class="nav-toggle__line"></span>
                <span class="nav-toggle__line"></span>
                <span class="nav-toggle__line"></span>
            </button>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Dynamische Blokken -->
    <main>
        <?php 
        if ($is_overlay_page) {
            // Laad de Prototype Sprint / Overlay header wrapper
            if (file_exists(__DIR__ . '/../components/sprint_header.php')) {
                include __DIR__ . '/../components/sprint_header.php';
            }
        }
        ?>

        <?php if (function_exists('render_page_blocks')): ?>
            <?= render_page_blocks($page['id']); ?>
        <?php else: ?>
            <p style="padding: 100px; text-align: center;">Geen blokken gevonden.</p>
        <?php endif; ?>

        <?php 
        if ($is_overlay_page) {
            // Laad de Prototype Sprint / Overlay footer wrapper
            if (file_exists(__DIR__ . '/../components/sprint_footer.php')) {
                include __DIR__ . '/../components/sprint_footer.php';
            }
        }
        ?>
    </main>

    <?php if (!$is_overlay_page): ?>
    <!-- Pagina-specifiek Footer Formulier -->
    <?php if (!empty($page['form_id'])): ?>
    <section class="page-footer-form" style="padding: 4rem 2rem; background: var(--color-surface); border-top: 1px solid var(--color-border);">
        <div class="container" style="max-width: 800px; margin: 0 auto;">
            <?= render_cta_block($page['form_id']) ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Standaard Footer -->
    <footer class="footer-bottom-wrapper" style="margin-top: 4rem;">
        <div class="footer-bottom-bar" style="padding: 2rem;">
            <div class="footer-bar__left">
                <img alt="Grut Logo Zwart" src="/assets/logo-beeldmerk.svg" width="40" style="opacity:0.3; filter: grayscale(1);"/>
            </div>
            <div class="footer-bar__center">
                <!-- Dynamisch Footer Menu -->
                <div style="display:flex; gap:1.5rem; justify-content:center; flex-wrap:wrap;">
                    <?= render_menu('footer', ['no_list' => true, 'a_class' => 'footer-link']) ?>
                </div>
            </div>
            <div class="footer-bar__right">
                <a class="social-icon" href="https://www.instagram.com/grutdesigners/" target="_blank">Instagram</a>
                <a class="social-icon" href="https://linkedin.com/company/grutdesigners" target="_blank">LinkedIn</a>
            </div>
        </div>
    </footer>
    <?php endif; ?>

    <style>
        .footer-link {
            color: #666;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .footer-link:hover {
            color: #fff;
        }
    </style>

    <!-- Overlays -->
    <?php 
    if (file_exists(__DIR__ . '/../components/global_overlays.php')) {
        include __DIR__ . '/../components/global_overlays.php';
    }
    ?>

    <!-- Scripts -->
    <script src="/script.min.js?v=33" defer></script>
</body>
</html>
