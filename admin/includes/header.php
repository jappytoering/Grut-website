<?php
require_once __DIR__ . '/../../includes/auth_helper.php';
AuthEngine::require_login();

if (isset($_GET['logout'])) {
    AuthEngine::logout();
    header("Location: /admin/login.php");
    exit;
}

// Bepaal actieve pagina voor navigatie
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grut Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #3A3480; /* Paars */
            --color-accent: #F3C033; /* Geel */
            --color-bg: #f4f6f8;
            --color-surface: #ffffff;
            --color-text: #333333;
            --color-text-light: #666666;
            --color-border: #e2e8f0;
            --sidebar-width: 260px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--color-primary);
            color: white;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
        }

        .sidebar-header {
            padding: 24px;
            font-size: 22px;
            font-weight: 800;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-nav {
            flex-grow: 1;
            padding: 20px 0;
        }

        .nav-item {
            display: block;
            padding: 14px 24px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }

        .nav-item:hover, .nav-item.active {
            background-color: rgba(255,255,255,0.1);
            color: var(--color-accent);
            border-left: 4px solid var(--color-accent);
            padding-left: 20px;
        }

        .sidebar-footer {
            padding: 20px 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 13px;
        }
        
        .sidebar-footer .user-email {
            display: block;
            opacity: 0.7;
            margin-bottom: 8px;
            word-break: break-all;
        }

        .logout-btn {
            color: var(--color-accent);
            text-decoration: none;
            font-weight: 800;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            padding: 40px;
            box-sizing: border-box;
            max-width: calc(100vw - var(--sidebar-width));
        }

        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: var(--color-primary);
        }

        /* Card / Table styles */
        .card {
            background: var(--color-surface);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.03);
            overflow: hidden;
        }
        
        /* Utility */
        .btn {
            background-color: var(--color-primary);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-family: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.9; }
        .btn-accent {
            background-color: var(--color-accent);
            color: var(--color-primary);
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            Grut Beheer
        </div>
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
            <a href="pages.php" class="nav-item <?php echo $current_page == 'pages.php' || $current_page == 'page_editor.php' ? 'active' : ''; ?>">Pagina's</a>
            <a href="components.php" class="nav-item <?php echo $current_page == 'components.php' ? 'active' : ''; ?>" style="padding-left: 40px; font-size: 0.9em; opacity: 0.9;">└ Globale Componenten</a>
            <a href="forms.php" class="nav-item <?php echo $current_page == 'forms.php' ? 'active' : ''; ?>">Formulieren</a>
            <a href="media.php" class="nav-item <?php echo $current_page == 'media.php' ? 'active' : ''; ?>">Media Hub</a>
            <a href="menus.php" class="nav-item <?php echo $current_page == 'menus.php' ? 'active' : ''; ?>">Inhoud menu's</a>
            <a href="content.php" class="nav-item <?php echo $current_page == 'content.php' ? 'active' : ''; ?>">Vertaal Sleutels (Legacy)</a>
        </nav>
        <div class="sidebar-footer">
            <span class="user-email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></span>
            <a href="dashboard.php?logout=1" class="logout-btn">Uitloggen</a>
        </div>
    </aside>

    <main class="main-content">
