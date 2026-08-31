<?php
require_once __DIR__ . '/includes/header.php';

$dbPath = __DIR__ . '/../storage/content.sqlite';
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$page_id = $_GET['id'] ?? null;
$page = null;
$blocks = [];

if ($page_id) {
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
    $stmt->execute([$page_id]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($page) {
        $stmt = $pdo->prepare("SELECT * FROM page_blocks WHERE page_id = ? ORDER BY sort_order ASC");
        $stmt->execute([$page_id]);
        $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<div class="admin-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="margin: 0;"><?= $page ? 'Pagina Bewerken: /' . htmlspecialchars($page['slug']) : 'Nieuwe Pagina Aanmaken' ?></h1>
    <a href="pages.php" class="btn btn-outline" style="border: 1px solid var(--color-border); color: var(--color-text); padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">Terug naar overzicht</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">
    <!-- Main Editor Area -->
    <div>
        <?php if ($page): ?>
            <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
                <h3 style="margin-top:0;">Pagina Blokken (Secties)</h3>
                <p style="color: var(--color-text-light); font-size: 0.9rem;">Voeg blokken toe en pas de velden per blok aan. Binnenkort kun je deze ook slepen.</p>
                
                <div id="blocks-container" style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach ($blocks as $block): ?>
                        <?php 
                        $content = json_decode($block['content_json'] ?? '{}', true);
                        ?>
                        <div class="block-editor-item" style="border: 1px solid var(--color-border); border-radius: 8px; overflow: hidden; background: #fff;">
                            <div class="block-header" style="background: #f8f9fa; padding: 1rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; border-bottom: 1px solid var(--color-border);">
                                <strong><?= htmlspecialchars(ucfirst($block['block_type'])) ?> Blok</strong>
                                <span style="font-size: 0.8rem; color: #888;">ID: <?= $block['id'] ?></span>
                            </div>
                            <div class="block-body" style="padding: 1rem;">
                                <!-- Simple JSON editor for now to scaffold the architecture -->
                                <label style="display:block; margin-bottom:0.5rem; font-weight:600; font-size:0.9rem;">Blok Content (JSON data voor component templates)</label>
                                <textarea class="block-json-input" data-id="<?= $block['id'] ?>" style="width:100%; height: 150px; padding:0.5rem; font-family: monospace; border:1px solid #ddd; border-radius:4px;"><?= htmlspecialchars(json_encode($content, JSON_PRETTY_PRINT)) ?></textarea>
                                <div style="margin-top:0.5rem; text-align:right;">
                                    <button class="btn btn-save-block" data-id="<?= $block['id'] ?>" style="font-size:0.85rem; padding:0.4rem 0.8rem;">Opslaan</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($blocks)): ?>
                        <div style="padding: 2rem; text-align: center; background: #f9f9f9; border-radius: 8px; color: #666; border: 1px dashed #ccc;">
                            Deze pagina heeft nog geen content.
                        </div>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 1.5rem; border-top: 1px solid var(--color-border); padding-top: 1.5rem;">
                    <h4>Nieuw blok toevoegen</h4>
                    <form id="add-block-form" style="display: flex; gap: 1rem; align-items: center;">
                        <input type="hidden" id="add-block-page-id" value="<?= $page_id ?>">
                        <select id="new-block-type" style="padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; flex-grow: 1;">
                            <option value="hero">Hero Sectie</option>
                            <option value="text_checklist">Tekst & Checklijst</option>
                            <option value="metrics">Statistieken / Metrics</option>
                            <option value="highlight">Highlight Quote / Banner</option>
                            <option value="faq">FAQ (Veelgestelde vragen)</option>
                            <option value="cta">Call to Action (Contact)</option>
                            <option value="logo_cloud">Klantlogo's</option>
                        </select>
                        <button type="submit" class="btn">Toevoegen</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="card" style="padding: 2rem; text-align: center;">
                <p>Vul de pagina-instellingen aan de rechterkant in en sla op om blokken te kunnen toevoegen.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar Settings Area -->
    <div>
        <div class="card" style="padding: 1.5rem;">
            <h3 style="margin-top:0;">Pagina Instellingen</h3>
            <form id="page-settings-form">
                <input type="hidden" id="page_id" value="<?= $page_id ?? '' ?>">
                
                <div style="margin-bottom: 1rem;">
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600;">URL Slug</label>
                    <input type="text" id="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" placeholder="bijv. over-ons" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600;">Status</label>
                    <select id="status" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Concept (Draft)</option>
                        <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Gepubliceerd</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600;">SEO Titel</label>
                    <input type="text" id="seo_title" value="<?= htmlspecialchars($page['seo_title'] ?? '') ?>" style="width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600;">Meta Beschrijving</label>
                    <textarea id="meta_description" style="width: 100%; height: 80px; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn" style="width: 100%;">Instellingen Opslaan</button>
            </form>
        </div>
    </div>
</div>

<script>
// Pagina instellingen opslaan
document.getElementById('page-settings-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        action: 'save_page',
        id: document.getElementById('page_id').value,
        slug: document.getElementById('slug').value,
        status: document.getElementById('status').value,
        seo_title: document.getElementById('seo_title').value,
        meta_description: document.getElementById('meta_description').value
    };

    try {
        const res = await fetch('/api/admin/cms_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.success) {
            alert('Pagina succesvol opgeslagen!');
            if (!data.id) {
                window.location.href = 'page_editor.php?id=' + json.id;
            }
        } else {
            alert('Fout: ' + json.error);
        }
    } catch(err) {
        alert('Netwerk fout');
    }
});

// Nieuw blok toevoegen
const addBlockForm = document.getElementById('add-block-form');
if(addBlockForm) {
    addBlockForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = {
            action: 'add_block',
            page_id: document.getElementById('add-block-page-id').value,
            block_type: document.getElementById('new-block-type').value
        };

        try {
            const res = await fetch('/api/admin/cms_actions.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if (json.success) {
                window.location.reload();
            } else {
                alert('Fout: ' + json.error);
            }
        } catch(err) {
            alert('Netwerk fout');
        }
    });
}

// Blok opslaan (JSON velden)
document.querySelectorAll('.btn-save-block').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        const id = e.target.getAttribute('data-id');
        const jsonInput = document.querySelector(`.block-json-input[data-id="${id}"]`).value;
        
        try {
            JSON.parse(jsonInput); // Valideer JSON
        } catch(e) {
            alert('Fout: Ongeldige JSON syntax.');
            return;
        }

        const data = {
            action: 'update_block',
            id: id,
            content_json: jsonInput
        };

        try {
            const res = await fetch('/api/admin/cms_actions.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if (json.success) {
                alert('Blok opgeslagen!');
            } else {
                alert('Fout: ' + json.error);
            }
        } catch(err) {
            alert('Netwerk fout');
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
