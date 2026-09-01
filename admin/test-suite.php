<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="admin-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="margin: 0;">Test Suite & Auto-Backlog</h1>
    <button id="run-tests-btn" class="btn btn-primary">Run Tests</button>
</div>

<div id="notification-banner" style="display: none; background-color: #fef3c7; color: #92400e; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #f59e0b; align-items: center; justify-content: space-between;">
    <span id="notification-message"></span>
    <div>
        <a href="https://github.com/jouw-repo/BACKLOG.md" target="_blank" class="btn btn-outline" style="background: white; border-color: #f59e0b; color: #92400e; padding: 0.4rem 0.8rem; text-decoration: none; display: inline-block;">Bekijk Backlog Story</a>
    </div>
</div>

<div class="panel">
    <div class="panel-header">Test Resultaten</div>
    <div class="panel-body" id="test-results-container" style="min-height: 200px; background: #f8f9fa;">
        <p style="color: #666; text-align: center; margin-top: 3rem;">Klik op "Run Tests" om te beginnen.</p>
    </div>
</div>

<style>
    .test-result-item {
        padding: 1rem;
        border-bottom: 1px solid var(--color-border);
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .test-result-item:last-child {
        border-bottom: none;
    }
    .test-status-pass {
        color: #10b981;
        font-weight: bold;
    }
    .test-status-fail {
        color: #ef4444;
        font-weight: bold;
    }
    .test-error-details {
        background: #fee2e2;
        color: #991b1b;
        padding: 0.75rem;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
</style>

<script>
document.getElementById('run-tests-btn').addEventListener('click', async () => {
    const btn = document.getElementById('run-tests-btn');
    const container = document.getElementById('test-results-container');
    const banner = document.getElementById('notification-banner');
    
    btn.disabled = true;
    btn.textContent = 'Running...';
    banner.style.display = 'none';
    container.innerHTML = '<p style="color: #666; text-align: center; margin-top: 3rem;">Tests worden uitgevoerd...</p>';
    
    try {
        const res = await fetch('/api/admin/run_tests.php');
        const data = await res.json();
        
        container.innerHTML = '';
        
        if (data.tests && data.tests.length > 0) {
            data.tests.forEach(test => {
                const item = document.createElement('div');
                item.className = 'test-result-item';
                
                const isPass = test.status === 'PASS';
                const statusSpan = `<span class="${isPass ? 'test-status-pass' : 'test-status-fail'}">[${test.status}]</span>`;
                
                let html = `<div>${statusSpan} <strong>${test.name}</strong></div>`;
                
                if (!isPass) {
                    html += `<div class="test-error-details">
                        <strong>Probleem:</strong> ${test.message}<br>
                        <strong>Bestand:</strong> ${test.file}
                    </div>`;
                }
                
                item.innerHTML = html;
                container.appendChild(item);
            });
        }
        
        // Laat notificatie banner zien als er nieuwe stories zijn
        if (data.new_stories_added > 0) {
            const msg = document.getElementById('notification-message');
            msg.innerHTML = `⚠️ <strong>${data.new_stories_added} probleem gevonden</strong> en automatisch als story toegevoegd aan BACKLOG.md.`;
            banner.style.display = 'flex';
        }
        
    } catch (e) {
        container.innerHTML = `<p style="color: red; padding: 1rem;">Er is een netwerkfout opgetreden bij het aanroepen van de tests.</p>`;
    } finally {
        btn.disabled = false;
        btn.textContent = 'Opnieuw testen';
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
