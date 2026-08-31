<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title">Welkom in het Dashboard</h1>
</div>

<div class="card" style="padding: 30px; border-left: 6px solid var(--color-accent);">
    <h2 style="margin-top: 0; color: var(--color-primary);">Goedemiddag!</h2>
    <p>Je bent succesvol ingelogd met rol: <strong><?php echo htmlspecialchars($_SESSION['user_role']); ?></strong>.</p>
    <p>Via de navigatie aan de linkerkant kun je de teksten op de website aanpassen of nieuwe foto's uploaden naar de Media Hub.</p>
    
    <div style="margin-top: 30px; display: flex; gap: 15px;">
        <a href="content.php" class="btn btn-accent" style="text-decoration: none;">Naar Teksten & Content</a>
        <a href="media.php" class="btn" style="text-decoration: none;">Naar Media Hub</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
