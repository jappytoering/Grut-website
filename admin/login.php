<?php

require_once __DIR__ . '/../includes/db_helper.php';
require_once __DIR__ . '/../includes/auth_helper.php';

// Als je al bent ingelogd, ga direct naar dashboard (later te bouwen)
if (AuthEngine::is_logged_in()) {
    header("Location: /admin/dashboard.php");
    exit;
}

// Seed default super_admin if table is empty
try {
    if (file_exists($dbPath)) {
        $pdo = get_cms_connection();
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
        if ($stmt && $stmt->fetchColumn() == 0) {
            $hash = password_hash('grut2026', PASSWORD_DEFAULT);
            $pdo->exec("INSERT INTO users (email, password_hash, role) VALUES ('info@grutdesigners.nl', '{$hash}', 'super_admin')");
        }
    }
} catch (Exception $e) {}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (AuthEngine::login($email, $password)) {
        header("Location: /admin/dashboard.php");
        exit;
    } else {
        $error = "Ongeldig e-mailadres of wachtwoord.";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grut Designers - Login</title>
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
            background-color: var(--color-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: var(--color-text);
        }

        .login-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            box-sizing: border-box;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: var(--color-primary);
            margin: 0;
            font-size: 28px;
            font-weight: 800;
        }

        .login-header p {
            color: #666;
            margin-top: 10px;
            font-size: 15px;
        }

        .error-message {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: var(--color-primary);
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            font-family: 'Outfit', sans-serif;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--color-accent);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--color-accent);
            color: var(--color-primary);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: transform 0.1s, background-color 0.2s;
            font-family: 'Outfit', sans-serif;
        }

        .btn-submit:hover {
            background-color: #e5b32a;
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <h1>Grut Beheer</h1>
            <p>Log in om teksten en media te beheren</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="/admin/login.php" method="POST">
            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" required autofocus placeholder="jouw@email.nl">
            </div>
            
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-submit">Inloggen</button>
        </form>
    </div>

</body>
</html>
