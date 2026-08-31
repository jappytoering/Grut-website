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

// Haal formulieren op voor de dropdown in het CTA blok
$forms_file = __DIR__ . '/../storage/forms.json';
$forms = file_exists($forms_file) ? json_decode(file_get_contents($forms_file), true) : [];
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
                                <option value="hero_home">Hero</option>
                                <option value="mission_statement">Missie / Intro</option>
                                <option value="services_grid">Diensten</option>
                                <option value="usp_venn">Venn Diagram (USP)</option>
                                <option value="portfolio_grid">Portfolio</option>
                                <option value="process_steps">Aanpak (Stappen)</option>
                                <option value="expertise_radar">Radar (Expertise)</option>
                                <option value="faq_home">FAQ (Homepage)</option>
                                <option value="cta_contact">Contact CTA</option>
                                <option value="prototype_sprint">Prototype Sprint (Volledige Pagina)</option>
                                <option value="card_grid">Card Grid (Standaard)</option>
                                <option value="text_intro">Tekst & Intro Blok (Standaard)</option>
                                <option value="steps_timeline">Stappenplan / Tijdlijn (Standaard)</option>
                                <option value="logo_cloud">Logo Cloud (Standaard)</option>
                                <option value="quote_highlight">Quote / Highlight (Standaard)</option>
                                <option value="text_block">Tekst Paragraaf (Overlay)</option>
                                <option value="tags_list">Tags Lijst (Overlay)</option>
                                <option value="image_block">Afbeelding (Overlay)</option>
                                <option value="meta_list">Meta Lijst / In het kort (Overlay)</option>
                                <option value="bullet_list">Checklist (Overlay)</option>
                                <option value="overlay_cta">Contact CTA (Overlay)</option>
                                <option value="hero">Standaard Hero (Titel & Intro)</option>
                                <option value="faq">Standaard FAQ</option>
                                <option value="cta_form">Call to Action (Formulier)</option>
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
                            <option value="default" <?= ($page['template'] ?? 'default') === 'default' ? 'selected' : '' ?>>Standaard Pagina</option>
                            <option value="overlay" <?= ($page['template'] ?? '') === 'overlay' ? 'selected' : '' ?>>Overlay / Modal</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>URL Slug (bijv. 'mijn-pagina')</label>
                        <input type="text" id="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" required>
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

<script>
// --- DATA INITIALISATIE ---
const formsData = <?= json_encode($forms) ?>;
const initialBlocks = <?= json_encode($blocks) ?>;

// Blok Schema's bepalen welke velden gerenderd worden per type
const blockSchemas = {
    hero_home: [
        { name: 'title', label: 'Titel (H1)', type: 'textarea' },
    ],
    mission_statement: [
        { name: 'title', label: 'Intro Titel', type: 'text' },
        { name: 'text', label: 'Missie Tekst', type: 'textarea' },
    ],
    services_grid: [
        { name: 'title', label: 'Diensten Titel', type: 'text' },
        { name: 'subtitle', label: 'Ondertitel', type: 'text' }
    ],
    usp_venn: [
        { name: 'title', label: 'Venn Diagram Titel', type: 'text' }
    ],
    portfolio_grid: [
        { name: 'title', label: 'Portfolio Titel', type: 'text' }
    ],
    process_steps: [
        { name: 'title', label: 'Aanpak Titel', type: 'text' }
    ],
    expertise_radar: [
        { name: 'title', label: 'Radar Titel', type: 'text' }
    ],
    faq_home: [
        { name: 'title', label: 'FAQ Titel', type: 'text' },
        { name: 'subtitle', label: 'FAQ Ondertitel', type: 'text' }
    ],
    cta_contact: [
        { name: 'title', label: 'CTA Titel', type: 'text' },
        { name: 'email', label: 'Email Adres', type: 'text' }
    ],
    prototype_sprint: [
        { name: 'info', label: 'Let op: Dit component laadt de volledige standalone Prototype Sprint layout in.', type: 'textarea' }
    ],
    card_grid: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'bg_color', label: 'Achtergrondkleur (bijv. var(--purple))', type: 'text' }
    ],
    text_intro: [
        { name: 'text', label: 'Grote Intro Tekst', type: 'textarea' },
        { name: 'bg_color', label: 'Achtergrondkleur', type: 'text' }
    ],
    steps_timeline: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'bg_color', label: 'Achtergrondkleur', type: 'text' }
    ],
    logo_cloud: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'bg_color', label: 'Achtergrondkleur', type: 'text' }
    ],
    quote_highlight: [
        { name: 'quote', label: 'Quote / Highlight Tekst', type: 'textarea' },
        { name: 'author', label: 'Auteur (optioneel)', type: 'text' },
        { name: 'bg_color', label: 'Achtergrondkleur', type: 'text' },
        { name: 'text_color', label: 'Tekstkleur', type: 'text' }
    ],
    text_block: [
        { name: 'title', label: 'Titel (optioneel)', type: 'text' },
        { name: 'text', label: 'Tekst', type: 'textarea' },
        { name: 'is_intro', label: 'Is Intro tekst? (true/false)', type: 'text' }
    ],
    tags_list: [
        { name: 'tags', label: 'Tags (komma gescheiden)', type: 'text' }
    ],
    image_block: [
        { name: 'image', label: 'Afbeelding URL (bijv. /assets/plaatje.webp)', type: 'text' },
        { name: 'alt', label: 'Alt tekst', type: 'text' }
    ],
    meta_list: [
        { name: 'title', label: 'Titel', type: 'text' },
        { name: 'label_1', label: 'Label 1', type: 'text' }, { name: 'value_1', label: 'Waarde 1', type: 'text' },
        { name: 'label_2', label: 'Label 2', type: 'text' }, { name: 'value_2', label: 'Waarde 2', type: 'text' },
        { name: 'label_3', label: 'Label 3', type: 'text' }, { name: 'value_3', label: 'Waarde 3', type: 'text' },
        { name: 'label_4', label: 'Label 4', type: 'text' }, { name: 'value_4', label: 'Waarde 4', type: 'text' }
    ],
    bullet_list: [
        { name: 'title', label: 'Titel (optioneel)', type: 'text' },
        { name: 'bullets', label: 'Checklist items (1 per regel)', type: 'textarea' }
    ],
    overlay_cta: [
        { name: 'title', label: 'Titel', type: 'textarea' },
        { name: 'subtitle', label: 'Subtitel', type: 'text' }
    ],
    hero: [
        { name: 'title', label: 'Titel (H1)', type: 'text' },
        { name: 'subtitle', label: 'Ondertitel / Intro', type: 'textarea' },
        { name: 'tag1', label: 'Tag 1 (Optioneel)', type: 'text' },
        { name: 'tag2', label: 'Tag 2 (Optioneel)', type: 'text' }
    ],
    faq: [
        { name: 'title', label: 'Sectie Titel', type: 'text' },
        { name: 'subtitle', label: 'Sectie Ondertitel', type: 'text' }
    ],
    cta_form: [
        { name: 'form_id', label: 'Koppel Formulier', type: 'select', options: formsData.map(f => ({value: f.id, label: f.title})) },
        { name: 'title', label: 'Titel Override (Optioneel)', type: 'text' },
        { name: 'subtitle', label: 'Ondertitel Override (Optioneel)', type: 'textarea' }
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

    // Opslaan naar DB
    try {
        const res = await fetch('/api/admin/cms_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                action: 'update_block',
                id: blockId,
                content_json: JSON.stringify(newContent)
            })
        });
        const json = await res.json();
        if(json.success) {
            alert('Blok succesvol opgeslagen');
            toggleForm(index);
        } else {
            alert('Fout bij opslaan: ' + json.error);
        }
    } catch(e) {
        alert('Netwerkfout bij opslaan blok.');
    }
};

window.deleteBlock = async (index, blockId) => {
    if (!confirm('Blok permanent verwijderen?')) return;
    
    try {
        const res = await fetch('/api/admin/cms_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'delete_block', id: blockId })
        });
        const json = await res.json();
        if(json.success) {
            blocks.splice(index, 1);
            renderBlocks();
        } else {
            alert('Fout bij verwijderen: ' + json.error);
        }
    } catch(e) {
        alert('Netwerkfout.');
    }
};

// Blok toevoegen
const btnAddBlock = document.getElementById('btn-add-block');
if(btnAddBlock) {
    btnAddBlock.addEventListener('click', async () => {
        const pageId = document.getElementById('page_id').value;
        const type = document.getElementById('new-block-type').value;
        
        try {
            const res = await fetch('/api/admin/cms_actions.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ action: 'add_block', page_id: pageId, block_type: type })
            });
            const json = await res.json();
            if(json.success) {
                // Herlaad pagina om frisse state uit DB te hebben (of voeg lokaal toe)
                window.location.reload();
            } else {
                alert('Fout bij toevoegen: ' + json.error);
            }
        } catch(e) {
            alert('Netwerkfout bij toevoegen blok.');
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
            try {
                await fetch('/api/admin/cms_actions.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ action: 'reorder_blocks', orders: orderData })
                });
                renderBlocks(); // Re-render voor kloppende indexes
            } catch(e) {
                alert('Fout bij opslaan volgorde.');
            }
        }
    });
    
    renderBlocks();
}

// --- PAGINA INSTELLINGEN OPSLAAN ---
document.getElementById('page-settings-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        action: 'save_page',
        id: document.getElementById('page_id').value,
        slug: document.getElementById('slug').value,
        status: document.getElementById('status').value,
        template: document.getElementById('template').value,
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
            alert('Pagina instellingen opgeslagen!');
            if (!data.id) {
                window.location.href = 'page_editor.php?id=' + json.id;
            } else {
                document.getElementById('status').style.background = data.status === 'published' ? '#d1fae5' : '#fef3c7';
            }
        } else {
            alert('Fout: ' + json.error);
        }
    } catch(err) {
        alert('Netwerk fout');
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
