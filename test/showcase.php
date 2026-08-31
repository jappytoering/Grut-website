<?php
require_once __DIR__ . '/../includes/form_helper.php';
$config = require __DIR__ . '/../config/contact.php';

if (!$config['debug']) {
    die('Testomgeving is alleen beschikbaar in debug modus.');
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testomgeving - Contactformulieren</title>
    <style>
        body { font-family: system-ui, sans-serif; line-height: 1.5; padding: 2rem; background: #f9f9f9; color: #333; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1, h2 { color: #111; }
        .section { margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 1px solid #eee; }
        
        /* Basic Form Styling */
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        input[type="text"], input[type="email"], input[type="tel"], textarea, select {
            width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        .has-error { border-color: #e53e3e !important; }
        .error-message { color: #e53e3e; font-size: 0.875rem; margin-top: 0.25rem; min-height: 1.2rem; }
        .form-error-message { color: #fff; background: #e53e3e; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .cta-success-message { color: #155724; background: #d4edda; border-color: #c3e6cb; padding: 1rem; border-radius: 4px; }
        
        .btn { display: inline-block; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; text-decoration: none; }
        .btn-primary { background: #000; color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #1a202c; }
        
        /* Radio Pills */
        .radio-pills-container { display: flex; gap: 1rem; flex-wrap: wrap; }
        .radio-pill input { display: none; }
        .pill-label { padding: 0.5rem 1rem; border: 1px solid #ccc; border-radius: 999px; cursor: pointer; display: inline-block; }
        .radio-pill input:checked + .pill-label { background: #000; color: #fff; border-color: #000; }

        /* Overlay */
        .contact-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000;
        }
        .contact-overlay.is-active { display: flex; }
        .overlay-content { background: #fff; padding: 2rem; border-radius: 8px; width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto; position: relative; }
        .overlay-close { position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; }

        /* Debug Panel */
        #debug-panel { background: #2d3748; color: #a0aec0; padding: 1rem; border-radius: 4px; font-family: monospace; white-space: pre-wrap; margin-top: 1rem; }
        .debug-actions { display: flex; gap: 1rem; margin-bottom: 1rem; }
    </style>
</head>
<body>

<div class="container">
    <h1>Contactformulieren Testomgeving</h1>
    
    <div class="debug-actions">
        <button id="btn-fill-dummy" class="btn btn-secondary">Vul Dummy Data in</button>
        <button id="btn-refresh-debug" class="btn btn-secondary">Ververs SQLite Log</button>
    </div>

    <div class="section">
        <h2>1. Quick Connect (Embedded)</h2>
        <?= render_cta_block('quick-connect') ?>
    </div>

    <div class="section">
        <h2>2. Project Intake (Overlay trigger)</h2>
        <a href="#" class="btn btn-primary" data-overlay-trigger="overlay-project">Open Project Intake</a>
    </div>

    <div class="section">
        <h2>3. Responsive Image (Centrale Beeldbank)</h2>
        <p>Voorbeeld van de <code>render_image()</code> output met WebP varianten en zero CLS.</p>
        <div style="max-width: 600px; border-radius: 8px; overflow: hidden; margin-top: 1rem;">
            <?php
            // We ensure media_helper is loaded
            require_once __DIR__ . '/../includes/media_helper.php';
            echo render_image('test-hero-image', [
                'alt' => 'Test hero image',
                'class' => 'test-image',
                'sizes' => '(max-width: 600px) 100vw, 600px'
            ]);
            ?>
        </div>
    </div>

    <div class="section">
        <h2>Live Debug Paneel (Laatste SQLite Entry)</h2>
        <div id="debug-panel">Klik op ververs...</div>
    </div>
</div>

<!-- Overlays -->
<div class="contact-overlay" id="overlay-project" aria-hidden="true">
    <div class="overlay-content">
        <button class="overlay-close" aria-label="Sluiten">&times;</button>
        <?= render_cta_block('project-intake', ['title' => 'Start je project vandaag']) ?>
    </div>
</div>

<!-- Scripts -->
<script src="/assets/js/contact-form.js"></script>
<script src="/assets/js/contact-overlay.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dummyBtn = document.getElementById('btn-fill-dummy');
        const refreshBtn = document.getElementById('btn-refresh-debug');
        const debugPanel = document.getElementById('debug-panel');

        dummyBtn.addEventListener('click', () => {
            document.querySelectorAll('form[data-contact-form]').forEach(form => {
                const inputs = {
                    'name': 'Jappy Toering Test',
                    'email': 'letsgo@grutdesigners.nl',
                    'phone': '0612345678',
                    'company': 'Grut Testbedrijf',
                    'message': 'Dit is een automatisch gegenereerd testbericht vanuit de debug tool.'
                };

                for (const [name, value] of Object.entries(inputs)) {
                    const input = form.querySelector(`[name="${name}"]`);
                    if (input) input.value = value;
                }

                // Check radio
                const radio = form.querySelector('input[type="radio"][value="ux-ui"]');
                if (radio) radio.checked = true;

                // Check select
                const select = form.querySelector('select[name="budget"]');
                if (select) select.value = '10k-25k';
            });
            alert('Dummy data ingevuld in alle formulieren!');
        });

        const refreshDebug = async () => {
            debugPanel.textContent = 'Laden...';
            try {
                const res = await fetch('/api/debug-db.php');
                const data = await res.json();
                debugPanel.textContent = JSON.stringify(data, null, 2);
            } catch (e) {
                debugPanel.textContent = 'Fout bij ophalen logs: ' + e;
            }
        };

        refreshBtn.addEventListener('click', refreshDebug);
        
        // Auto-refresh upon successful submit (intercepting the event from contact-form.js is tricky, 
        // so we'll just poll every 3 seconds if debug is open, or just let user click refresh).
        setInterval(refreshDebug, 5000);
        refreshDebug();
    });
</script>

</body>
</html>
