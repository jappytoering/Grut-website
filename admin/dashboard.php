<?php
require_once __DIR__ . '/../includes/auth_helper.php';

AuthEngine::require_login();

// Logout logic
if (isset($_GET['logout'])) {
    AuthEngine::logout();
    header("Location: /admin/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grut Designers - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #3A3480; /* Paars */
            --color-accent: #F3C033; /* Geel */
            --color-bg: #f9f9f9;
            --color-text: #333333;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        .header {
            background-color: var(--color-primary);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }

        .logout-btn {
            background-color: var(--color-accent);
            color: var(--color-primary);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 800;
            font-size: 14px;
            transition: opacity 0.2s;
        }

        .logout-btn:hover {
            opacity: 0.9;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 40px;
        }

        .welcome-card {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 6px solid var(--color-accent);
        }
        
        .welcome-card h2 {
            margin-top: 0;
            color: var(--color-primary);
        }
    </style>
</head>
<body>

    <header class="header">
        <h1>Grut Beheer</h1>
        <div>
            <span style="margin-right: 20px; font-size: 14px; opacity: 0.8;">Ingelogd als: <?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
            <a href="?logout=1" class="logout-btn">Uitloggen</a>
        </div>
    </header>

    <div class="container">
        <div class="welcome-card">
            <h2>Welkom in het dashboard</h2>
            <p>Je bent succesvol ingelogd! Jouw rol is: <strong><?php echo htmlspecialchars($_SESSION['user_role']); ?></strong>.</p>
            <p>Binnenkort kun je hier de website teksten en de beeldbank (Media Hub) beheren.</p>
        </div>
    </div>

</body>
</html>
