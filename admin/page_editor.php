<?php

require_once __DIR__ . '/../includes/db_helper.php';
require_once __DIR__ . '/includes/header.php';

$pdo = get_cms_connection();
$page_id = $_GET['id'] ?? null;
$page = null;
$blocks = [];

if (!$page_id) {
    // Auto-create een draft pagina zodat je direct blokken kunt toevoegen
    $temp_slug = 'concept-' . bin2hex(random_bytes(4));
    $stmt = $pdo->prepare("INSERT INTO pages (slug, status, template, seo_title, meta_description, created_at) VALUES (?, 'draft', 'default', 'Nieuwe Pagina', '', CURRENT_TIMESTAMP)");
    $stmt->execute([$temp_slug]);
    $new_id = $pdo->lastInsertId();
    header("Location: page_editor.php?id={$new_id}&new=1");
    exit;
}

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

// Haal formulieren op voor de dropdown (Footer CTA)
$forms = [];
try {
    $stmt = $pdo->query("SELECT id, title FROM forms ORDER BY title ASC");
    $forms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
?>

<!-- CDN voor Drag & Drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<style>
    .editor-layout { display: grid; grid-template-columns: 1fr 350px; gap: 2rem; align-items: start; }
    
    .panel { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.03); border: 1px solid var(--color-border); overflow: hidden; margin-bottom: 2rem; }
    .panel-header { padding: 1rem 1.5rem; border-bottom: 1px solid var(--color-border); font-weight: 800; font-size: 1.1rem; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center; }
    .panel-body { padding: 1.5rem; }
    
    /* Block Item (Draggable) */
    .block-item { background: #fff; border: 1px solid var(--color-border); border-radius: 8px; margin-bottom: 1rem; overflow: hidden; transition: box-shadow 0.2s; }
    .block-item:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .block-header { padding: 1rem; background: #f8f9fa; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; cursor: grab; }
    .block-header:active { cursor: grabbing; }
    .block-title { font-weight: 600; color: var(--color-primary); }
    .block-actions button { background: none; border: none; cursor: pointer; color: #666; font-size: 0.85rem; margin-left: 0.5rem; }
    .block-actions button:hover { color: var(--color-primary); }
    .block-actions .btn-delete { color: red; }
    
    /* Inline Forms */
    .block-form { padding: 1.5rem; background: #fafafa; display: none; }
    .block-form.open { display: block; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; margin-bottom: 0.3rem; font-size: 0.85rem; font-weight: 600; }
    .form-group input[type="text"], .form-group select, .form-group textarea { width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .form-group textarea { min-height: 80px; resize: vertical; }
    
    /* Sortable Placeholder */
    .sortable-ghost { opacity: 0.4; background: #f0f0f0; border: 1px dashed #999; }
    
    /* Media Modal */
    .media-modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
    .media-modal.open { display: flex; }
    .media-modal-content { background: white; padding: 2rem; border-radius: 12px; width: 90%; max-width: 900px; max-height: 80vh; overflow-y: auto; }
    .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem; }
    .media-item { cursor: pointer; border: 2px solid transparent; border-radius: 8px; overflow: hidden; transition: 0.2s; }
    .media-item:hover { border-color: var(--color-primary); }
    .media-item img { width: 100%; height: 120px; object-fit: cover; display: block; }
</style>

<div class="admin-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="margin: 0;">Pagina Builder</h1>
    <div>
        <?php if ($page): ?>
            <a href="/<?= htmlspecialchars($page['slug']) ?>" target="_blank" class="btn btn-outline" style="margin-right: 0.5rem;">Preview Pagina</a>
        <?php endif; ?>
        <a href="pages.php" class="btn btn-outline">Terug naar overzicht</a>
    </div>
</div>

<div class="editor-layout">
    <!-- Linker Kolom: Blokken Canvas -->
    <div>
        <?php if ($page): ?>
            <div class="panel">
                <div class="panel-header">
                    Inhoud (Blokken)
                    <span style="font-size: 0.8rem; font-weight: normal; color: #666;">Sleep om te herschikken</span>
                </div>
                <div class="panel-body" style="background: #f4f6f8;">
                    
                    <div id="blocks-canvas" style="min-height: 100px;">
                        <!-- Blokken worden hier gerenderd via JS -->
                    </div>

                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed #ccc;">
                        <h4 style="margin-top:0;">Nieuw blok toevoegen</h4>
                        <div style="display: flex; gap: 0.5rem;">
                            <select id="new-block-type" style="padding: 0.6rem; border: 1px solid #ccc; border-radius: 4px; flex-grow: 1;">
                                <optgroup label="Prototype Sprint">
                                    <option value="sprint_hero">Sprint: Hero</option>
                                    <option value="sprint_slider">Sprint: Image Slider</option>
                                    <option value="sprint_text_checklist">Sprint: Tekst + Checklist</option>
                                    <option value="sprint_meta_list">Sprint: In het kort (Meta)</option>
                                    <option value="sprint_fit">Sprint: Geschikt voor?</option>
                                    <option value="sprint_highlight_block">Sprint: Uitgelicht blok</option>
                                    <option value="sprint_columns_block">Sprint: 2-Koloms Opsomming</option>
                                    <option value="sprint_days_list">Sprint: Dagen Stappenplan</option>
                                    <option value="sprint_faq">Sprint: FAQ Accordion</option>
                                    <option value="sprint_trusted_logos">Sprint: Trusted Logos</option>
                                    <option value="sprint_cta_block">Sprint: CTA Formulier</option>
                                </optgroup>
                                <option value="default">Standaard Tekst</option>
                            </select>
                            <button id="btn-add-block" class="btn btn-primary">Toevoegen</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="panel">
                <div class="panel-body" style="text-align: center; padding: 4rem 2rem;">
                    <h3>Nieuwe Pagina</h3>
                    <p style="color: #666;">Vul eerst de instellingen aan de rechterkant in en klik op 'Opslaan' om de pagina aan te maken. Daarna kun je blokken toevoegen.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Rechter Kolom: SEO & Instellingen -->
    <div>
        <div class="panel">
            <div class="panel-header">Pagina Instellingen</div>
            <div class="panel-body">
                <form id="page-settings-form">
                    <input type="hidden" id="page_id" value="<?= $page_id ?? '' ?>">
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select id="status" style="background: <?= ($page['status'] ?? '') === 'published' ? '#d1fae5' : '#fef3c7' ?>; font-weight: bold;">
                            <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Concept (Alleen ingelogden)</option>
                            <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Gepubliceerd (Live)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Template</label>
                        <select id="template">
                            <option value="default" <?= ($page['template'] ?? 'default') === 'default' ? 'selected' : '' ?>>Standaard Pagina (Licht, met nav/footer)</option>
                            <option value="overlay" <?= ($page['template'] ?? '') === 'overlay' ? 'selected' : '' ?>>Prototype Sprint (Donker, gecentreerd)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>URL Slug (bijv. 'mijn-pagina')</label>
                        <input type="text" id="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" required>
                        <small id="slug_warning" style="color:red; font-size:0.75rem; display:none;"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Gekoppeld Contactformulier (Footer)</label>
                        <select id="form_id">
                            <option value="">-- Geen formulier --</option>
                            <?php foreach ($forms as $f): ?>
                                <option value="<?= $f['id'] ?>" <?= ($page['form_id'] ?? '') == $f['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($f['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small style="color:gray; font-size:0.75rem;">Wordt weergegeven in de standaard page footer.</small>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--color-border); margin: 1.5rem 0;">
                    
                    <h4 style="margin-top:0; font-size:0.95rem; color:var(--color-primary);">SEO & OpenGraph</h4>
                    
                    <div class="form-group">
                        <label>Meta Titel</label>
                        <input type="text" id="seo_title" value="<?= htmlspecialchars($page['seo_title'] ?? '') ?>">
                        <small style="color:gray; font-size:0.75rem;">Aanbevolen: max 60 tekens</small>
                    </div>

                    <div class="form-group">
                        <label>Meta Beschrijving</label>
                        <textarea id="meta_description" rows="3"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                        <small style="color:gray; font-size:0.75rem;">Aanbevolen: max 155 tekens</small>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Pagina Opslaan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Media Picker Modal -->
<div class="media-modal" id="media-modal">
    <div class="media-modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2 style="margin:0;">Kies Afbeelding</h2>
            <button type="button" class="btn btn-outline" onclick="closeMediaModal()">Sluiten</button>
        </div>
        <div id="media-modal-grid" class="media-grid">
            Loading...
        </div>
    </div>
</div>

<script>
// --- DATA INITIALISATIE ---
const formsData = <?= json_encode($forms) ?>;
const initialBlocks = <?= json_encode($blocks) ?>;

// Blok Schema's bepalen welke velden gerenderd worden per type
const blockSchemas = {
    sprint_hero: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'intro', label: 'Introductie Tekst', type: 'textarea' },
        { name: 'tags', label: 'Tags (komma gescheiden)', type: 'text' }
    ],
    sprint_slider: [
        { name: 'title', label: 'Sectie Titel', type: 'text' },
        { name: 'subtitle', label: 'Sectie Ondertitel', type: 'textarea' }
    ],
    sprint_text_checklist: [
        { name: 'title', label: 'Sectie Titel', type: 'text' },
        { name: 'intro', label: 'Introductie Tekst', type: 'textarea' },
        { name: 'checklist', label: 'Checklist Items (1 per regel)', type: 'textarea' }
    ],
    sprint_meta_list: [
        { name: 'title', label: 'Sectie Titel', type: 'text' },
        { name: 'label_1', label: 'Label 1', type: 'text' }, { name: 'value_1', label: 'Waarde 1', type: 'text' },
        { name: 'label_2', label: 'Label 2', type: 'text' }, { name: 'value_2', label: 'Waarde 2', type: 'text' },
        { name: 'label_3', label: 'Label 3', type: 'text' }, { name: 'value_3', label: 'Waarde 3', type: 'text' },
        { name: 'label_4', label: 'Label 4', type: 'text' }, { name: 'value_4', label: 'Waarde 4', type: 'text' }
    ],
    sprint_fit: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'text', label: 'Tekst', type: 'textarea' }
    ],
    sprint_highlight_block: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'text', label: 'Tekst', type: 'textarea' },
        { name: 'checklist', label: 'Checklist Items (1 per regel)', type: 'textarea' },
        { name: 'image', label: 'Afbeelding ID/URL', type: 'image' }
    ],
    sprint_columns_block: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'checklist', label: 'Checklist Items (1 per regel)', type: 'textarea' }
    ],
    sprint_days_list: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'day_1_title', label: 'Dag 1 Titel', type: 'text' }, { name: 'day_1_text', label: 'Dag 1 Tekst', type: 'textarea' },
        { name: 'day_2_title', label: 'Dag 2 Titel', type: 'text' }, { name: 'day_2_text', label: 'Dag 2 Tekst', type: 'textarea' },
        { name: 'day_3_title', label: 'Dag 3 Titel', type: 'text' }, { name: 'day_3_text', label: 'Dag 3 Tekst', type: 'textarea' },
        { name: 'day_4_title', label: 'Dag 4 Titel', type: 'text' }, { name: 'day_4_text', label: 'Dag 4 Tekst', type: 'textarea' },
        { name: 'day_5_title', label: 'Dag 5 Titel', type: 'text' }, { name: 'day_5_text', label: 'Dag 5 Tekst', type: 'textarea' }
    ],
    sprint_faq: [
        { name: 'title', label: 'Sectie Titel', type: 'text' },
        { name: 'q1', label: 'Vraag 1', type: 'text' }, { name: 'a1', label: 'Antwoord 1', type: 'textarea' },
        { name: 'q2', label: 'Vraag 2', type: 'text' }, { name: 'a2', label: 'Antwoord 2', type: 'textarea' },
        { name: 'q3', label: 'Vraag 3', type: 'text' }, { name: 'a3', label: 'Antwoord 3', type: 'textarea' },
        { name: 'q4', label: 'Vraag 4', type: 'text' }, { name: 'a4', label: 'Antwoord 4', type: 'textarea' },
        { name: 'q5', label: 'Vraag 5', type: 'text' }, { name: 'a5', label: 'Antwoord 5', type: 'textarea' }
    ],
    sprint_trusted_logos: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'logos', label: 'Logos CSV (Tijdelijk textveld voor image ID\'s)', type: 'textarea' }
    ],
    sprint_cta_block: [
        { name: 'form_id', label: 'Koppel Formulier', type: 'select', options: formsData.map(f => ({value: f.id, label: f.title})) },
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'price', label: 'Prijs Indicatie (optioneel)', type: 'text' },
        { name: 'checklist', label: 'Checklist Items (1 per regel)', type: 'textarea' }
    ],
    default: [
        { name: 'content', label: 'HTML Inhoud', type: 'textarea' }
    ]
};

// State
let blocks = initialBlocks.map(b => ({
    id: b.id,
    type: b.block_type,
    order: b.sort_order,
    content: JSON.parse(b.content_json || '{}')
}));

// --- RENDER LOGICA ---
const canvas = document.getElementById('blocks-canvas');

function renderBlocks() {
    if (!canvas) return;
    canvas.innerHTML = '';
    
    if (blocks.length === 0) {
        canvas.innerHTML = '<div style="padding: 2rem; text-align: center; color: #666; border: 1px dashed #ccc; border-radius: 8px;">Nog geen blokken. Voeg er één toe!</div>';
        return;
    }

    // Sorteer visueel
    blocks.sort((a,b) => a.order - b.order).forEach((block, index) => {
        const schema = blockSchemas[block.type] || blockSchemas['default'];
        
        // Bouw de form HTML dynamisch op basis van het schema
        let formHtml = '';
        schema.forEach(field => {
            const val = block.content[field.name] || '';
            formHtml += `<div class="form-group"><label>${field.label}</label>`;
            
            if (field.type === 'text') {
                formHtml += `<input type="text" data-field="${field.name}" value="${val.replace(/"/g, '&quot;')}">`;
            } else if (field.type === 'textarea') {
                formHtml += `<textarea data-field="${field.name}">${val}</textarea>`;
            } else if (field.type === 'image') {
                formHtml += `<div style="display:flex; gap: 1rem; align-items: center;">
                    <div style="flex-grow:1;">
                        <input type="text" data-field="${field.name}" value="${val.replace(/"/g, '&quot;')}" id="img-input-${index}-${field.name}" placeholder="Selecteer of plak een URL...">
                        <button type="button" class="btn btn-outline" style="margin-top: 0.5rem;" onclick="openMediaModal('img-input-${index}-${field.name}')">Kies uit Media Hub</button>
                    </div>
                    <div>
                        <img src="${val ? val : ''}" id="img-preview-${index}-${field.name}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #ccc; display: ${val ? 'block' : 'none'};">
                    </div>
                </div>`;
            } else if (field.type === 'select') {
                formHtml += `<select data-field="${field.name}">
                    <option value="">-- Selecteer --</option>
                    ${field.options.map(opt => `<option value="${opt.value}" ${opt.value === val ? 'selected' : ''}>${opt.label}</option>`).join('')}
                </select>`;
            }
            formHtml += `</div>`;
        });

        const div = document.createElement('div');
        div.className = 'block-item';
        div.dataset.index = index;
        div.dataset.id = block.id; // DB id
        
        div.innerHTML = `
            <div class="block-header">
                <span class="block-title">☰ ${block.type.toUpperCase()} BLOK</span>
                <div class="block-actions">
                    <button type="button" onclick="toggleForm(${index})">Bewerken</button>
                    <button type="button" class="btn-delete" onclick="deleteBlock(${index}, ${block.id})">Verwijder</button>
                </div>
            </div>
            <div class="block-form" id="form-${index}">
                ${formHtml}
                <div style="text-align: right; margin-top: 1rem;">
                    <button type="button" class="btn btn-primary" onclick="saveBlockData(${index}, ${block.id})">Inhoud Opslaan</button>
                </div>
            </div>
        `;
        canvas.appendChild(div);
    });
}

// --- INTERACTIES ---
window.toggleForm = (index) => {
    document.getElementById(`form-${index}`).classList.toggle('open');
};

window.saveBlockData = async (index, blockId) => {
    const formEl = document.getElementById(`form-${index}`);
    const block = blocks[index];
    
    // Verzamel data
    const inputs = formEl.querySelectorAll('[data-field]');
    const newContent = {};
    inputs.forEach(input => {
        newContent[input.dataset.field] = input.value;
    });
    
    block.content = newContent; // Update lokaal

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Opslaan naar DB
    try {
        const res = await fetch('/api/admin/cms_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken}, credentials: 'same-origin',
            body: JSON.stringify({
                action: 'update_block', csrf_token: csrfToken,
                id: blockId,
                content_json: JSON.stringify(newContent)
            })
        });
        const json = await res.json();
        if(json.success) {
            window.isDirty = false;
            alert('Blok succesvol opgeslagen');
            toggleForm(index);
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast('Fout bij opslaan: ' + json.error, 'error');
        }
    } catch(e) {
        showToast('Netwerkfout bij opslaan blok.', 'error');
    }
};

// Track changes in block forms
canvas.addEventListener('input', (e) => {
    if (e.target.matches('[data-field]')) {
        window.isDirty = true;
    }
});

window.deleteBlock = async (index, blockId) => {
    if (!confirm('Blok permanent verwijderen?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        const res = await fetch('/api/admin/cms_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken}, credentials: 'same-origin',
            body: JSON.stringify({ action: 'delete_block', csrf_token: csrfToken, id: blockId })
        });
        const json = await res.json();
        if (json.success) {
            showToast('Blok verwijderd', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast('Fout bij verwijderen: ' + json.error, 'error');
        }
    } catch(e) {
        showToast('Netwerkfout.', 'error');
    }
};

// Blok toevoegen
const btnAddBlock = document.getElementById('btn-add-block');
if(btnAddBlock) {
    btnAddBlock.addEventListener('click', async () => {
        const pageId = document.getElementById('page_id').value;
        const type = document.getElementById('new-block-type').value;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            const res = await fetch('/api/admin/cms_actions.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken}, credentials: 'same-origin',
                body: JSON.stringify({ action: 'add_block', csrf_token: csrfToken, page_id: pageId, block_type: type })
            });
            const json = await res.json();
            if(json.success) {
                // Herlaad pagina om frisse state uit DB te hebben (of voeg lokaal toe)
                window.location.reload();
            } else {
                showToast('Fout bij toevoegen: ' + json.error, 'error');
            }
        } catch(e) {
            showToast('Netwerkfout bij toevoegen blok.', 'error');
        }
    });
}

// Initialiseer drag & drop
if (canvas) {
    new Sortable(canvas, {
        animation: 150,
        handle: '.block-header',
        ghostClass: 'sortable-ghost',
        onEnd: async function(evt) {
            // Update lokale array volgorde
            const item = blocks.splice(evt.oldIndex, 1)[0];
            blocks.splice(evt.newIndex, 0, item);
            
            // Stuur nieuwe volgorde naar server (batch update)
            const orderData = blocks.map((b, i) => ({ id: b.id, sort_order: i }));
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const res = await fetch('/api/admin/cms_actions.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken}, credentials: 'same-origin',
                    body: JSON.stringify({ action: 'reorder_blocks', csrf_token: csrfToken, orders: orderData })
                });
                const json = await res.json();
                if (!json.success) {
                    showToast('Fout bij opslaan volgorde.', 'error');
                } else {
                    showToast('Volgorde opgeslagen', 'success');
                }
            } catch(e) {
                showToast('Fout bij opslaan volgorde.', 'error');
            }
        }
    });
    
    renderBlocks();
}

// --- PAGINA INSTELLINGEN OPSLAAN ---
const settingsForm = document.getElementById('page-settings-form');
settingsForm.addEventListener('input', () => window.isDirty = true);

const slugInput = document.getElementById('slug');
let slugTimeout = null;
slugInput.addEventListener('input', () => {
    clearTimeout(slugTimeout);
    const slug = slugInput.value;
    const excludeId = document.getElementById('page_id').value;
    const warning = document.getElementById('slug_warning');
    
    if (slug.length < 2) {
        warning.style.display = 'none';
        return;
    }
    
    slugTimeout = setTimeout(async () => {
        try {
            const res = await fetch(`/api/admin/check_slug.php?slug=${encodeURIComponent(slug)}&exclude_id=${excludeId}`);
            const json = await res.json();
            if (json.exists) {
                warning.textContent = json.message;
                warning.style.display = 'block';
            } else {
                warning.style.display = 'none';
            }
        } catch(e) {}
    }, 500);
});

settingsForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const data = {
        action: 'save_page', csrf_token: csrfToken,
        id: document.getElementById('page_id').value,
        slug: document.getElementById('slug').value,
        status: document.getElementById('status').value,
        template: document.getElementById('template').value,
        form_id: document.getElementById('form_id').value,
        seo_title: document.getElementById('seo_title').value,
        meta_description: document.getElementById('meta_description').value
    };

    try {
        const res = await fetch('/api/admin/cms_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken}, credentials: 'same-origin',
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.success) {
            window.isDirty = false;
            showToast('Pagina instellingen opgeslagen!', 'success');
            if (!data.id) {
                setTimeout(() => {
                    window.location.href = 'page_editor.php?id=' + json.id;
                }, 1000);
            } else {
                document.getElementById('status').style.background = data.status === 'published' ? '#d1fae5' : '#fef3c7';
            }
        } else {
            showToast('Fout: ' + json.error, 'error');
        }
    } catch(err) {
        showToast('Netwerk fout', 'error');
    }
});

// --- MEDIA MODAL LOGICA ---
let activeMediaInputId = null;

async function openMediaModal(inputId) {
    activeMediaInputId = inputId;
    const modal = document.getElementById('media-modal');
    modal.classList.add('open');
    
    const grid = document.getElementById('media-modal-grid');
    grid.innerHTML = 'Laden...';
    
    try {
        const res = await fetch('/api/admin/media_api.php?action=list');
        const json = await res.json();
        if (json.success) {
            if (json.data.length === 0) {
                grid.innerHTML = '<p>Geen afbeeldingen gevonden. Ga naar Media Hub om er een te uploaden.</p>';
                return;
            }
            
            grid.innerHTML = json.data.map(asset => {
                // Get display path (original or variant)
                let displaySrc = '/storage/media/originals/' + (asset.original_filename || asset.asset_id);
                try {
                    const variants = JSON.parse(asset.variants_json);
                    if (variants && variants.large) {
                        displaySrc = '/' + variants.large.path;
                    }
                } catch(e) {}
                
                return `
                    <div class="media-item" onclick="selectMedia('${asset.asset_id}', '${displaySrc}')">
                        <img src="${displaySrc}" alt="${asset.alt_text || ''}" loading="lazy">
                        <div style="font-size:0.75rem; padding:0.5rem; word-break:break-all;">${asset.original_filename}</div>
                    </div>
                `;
            }).join('');
        }
    } catch(e) {
        grid.innerHTML = 'Fout bij laden van media.';
    }
}

function closeMediaModal() {
    document.getElementById('media-modal').classList.remove('open');
    activeMediaInputId = null;
}

function selectMedia(assetId, displayPath) {
    if (activeMediaInputId) {
        const input = document.getElementById(activeMediaInputId);
        if (input) {
            input.value = assetId; // We store the asset_id so the frontend can use render_image
            // Update local preview
            const previewId = activeMediaInputId.replace('img-input-', 'img-preview-');
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = displayPath;
                preview.style.display = 'block';
            }
        }
    }
    closeMediaModal();
}
</script>

<?php if (isset($_GET['new']) && $_GET['new'] == 1): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    showToast('Nieuwe conceptpagina aangemaakt! Je kunt nu direct blokken toevoegen.', 'success');
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
