<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/auth_helper.php';

// Only super_admin can access this page
if (!AuthEngine::has_role('super_admin')) {
    echo "<div style='padding: 2rem;'><h2>Toegang geweigerd</h2><p>Je hebt niet de juiste rechten om deze pagina te bekijken.</p></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>

<div class="page-header">
    <h1 class="page-title">Deploy naar Test</h1>
</div>

<div class="card" style="padding: 30px; max-width: 600px;">
    <h2>Publiceer Wijzigingen</h2>
    <p style="color: var(--color-text-light); line-height: 1.6;">
        Door op <strong>Deploy</strong> te klikken, worden de huidige database en formulierconfiguraties gekopieerd naar "seed" bestanden. 
        Vervolgens wordt dit automatisch gecommit en naar Github gepusht op de <code>test</code> branch.
    </p>
    <div style="background: #fef3c7; color: #92400e; padding: 15px; border-radius: 8px; margin-top: 20px; font-size: 14px;">
        <strong>Let op:</strong> Nadat de deploy in Github klaar is (~60 sec), moet je nog op de testserver de nieuwe database activeren door naar <a href="https://test.grutdesigners.nl/sync_seed.php" target="_blank" style="color:#b45309; font-weight:bold; text-decoration:underline;">sync_seed.php</a> te navigeren!
    </div>
    
    <div style="margin-top: 30px;">
        <button class="btn btn-accent" id="btn-deploy" onclick="triggerDeploy()" style="font-size: 18px; padding: 15px 30px;">
            🚀 Start Deploy
        </button>
    </div>
    
    <div id="deploy-log" style="display: none; margin-top: 30px; background: #1e293b; color: #10b981; padding: 20px; border-radius: 8px; font-family: monospace; white-space: pre-wrap; font-size: 13px;"></div>
</div>

<script>
async function triggerDeploy() {
    const btn = document.getElementById('btn-deploy');
    const logContainer = document.getElementById('deploy-log');
    
    if (!confirm("Weet je zeker dat je de wijzigingen wilt publiceren?")) return;
    
    btn.disabled = true;
    btn.innerText = '⏳ Bezig met deployen...';
    logContainer.style.display = 'block';
    logContainer.innerText = 'Starten van background worker...\n';
    
    try {
        const res = await fetch('/api/admin/deploy_action.php', { method: 'POST' });
        const json = await res.json();
        
        if (json.success) {
            logContainer.innerText += '✅ Succesvol!\n\n' + json.output;
            btn.innerText = '✅ Gedaan';
        } else {
            logContainer.innerText += '❌ Fout opgetreden:\n' + json.error;
            btn.innerText = '❌ Mislukt';
            btn.disabled = false;
        }
    } catch(e) {
        logContainer.innerText += '❌ Netwerkfout.';
        btn.innerText = '❌ Mislukt';
        btn.disabled = false;
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
