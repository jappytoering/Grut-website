<?php 
require_once __DIR__ . "/includes/header.php"; 

$forms_file = __DIR__ . '/../storage/forms.json';
$forms = [];
if (file_exists($forms_file)) {
    $forms = json_decode(file_get_contents($forms_file), true) ?? [];
}
?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<style>
    .builder-layout {
        display: grid;
        grid-template-columns: 250px 1fr 300px;
        gap: 1.5rem;
        height: calc(100vh - 160px);
    }
    
    .panel {
        background: var(--color-surface);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    
    .panel-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--color-border);
        font-weight: 800;
        font-size: 1.1rem;
        background: #f8f9fa;
    }
    
    .panel-body {
        padding: 1.5rem;
        overflow-y: auto;
        flex-grow: 1;
    }

    /* Palette */
    .palette-btn {
        display: block;
        width: 100%;
        padding: 0.8rem;
        margin-bottom: 0.8rem;
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 6px;
        text-align: left;
        cursor: grab;
        font-weight: 600;
        transition: border-color 0.2s;
    }
    .palette-btn:hover { border-color: var(--color-primary); }

    /* Canvas */
    .canvas-area {
        min-height: 100%;
        padding-bottom: 4rem;
    }
    .field-card {
        background: white;
        border: 1px solid var(--color-border);
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: grab;
        position: relative;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .field-card.selected {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(58,52,128,0.2);
    }
    .field-card-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: var(--color-text-light);
    }
    .field-card-title {
        font-weight: 800;
        color: var(--color-primary);
        font-size: 1rem;
    }
    .delete-field-btn {
        background: none;
        border: none;
        color: red;
        cursor: pointer;
        font-size: 0.8rem;
    }

    /* Props */
    .prop-group { margin-bottom: 1rem; }
    .prop-group label { display: block; font-size: 0.85rem; margin-bottom: 0.3rem; font-weight: 600; }
    .prop-group input, .prop-group select, .prop-group textarea {
        width: 100%; padding: 0.6rem; border: 1px solid var(--color-border); border-radius: 4px; box-sizing: border-box;
    }
    .prop-group input[type="checkbox"] { width: auto; margin-right: 0.5rem; }
</style>

<div class="admin-header-flex" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="forms.php" class="btn" style="background: transparent; color: var(--color-primary); padding: 0.4rem 0.8rem; font-size: 0.9rem; border: 1px solid var(--color-border);">&larr; Terug</a>
        <h1 style="margin: 0; font-size:24px;">Form Builder</h1>
    </div>
    <button class="btn btn-accent" id="save-forms-btn">Opslaan</button>
</div>

<div class="builder-layout">
    <!-- Links: Palet -->
    <div class="panel">
        <div class="panel-header">Veldtypen</div>
        <div class="panel-body" id="palette">
            <button class="palette-btn" data-type="text">Tekstveld</button>
            <button class="palette-btn" data-type="email">E-mailadres</button>
            <button class="palette-btn" data-type="tel">Telefoon</button>
            <button class="palette-btn" data-type="textarea">Tekstvak (Groot)</button>
            <button class="palette-btn" data-type="select">Dropdown (Select)</button>
            <button class="palette-btn" data-type="radio-pills">Radio Knoppen</button>
            <button class="palette-btn" data-type="checkbox">Checkbox</button>
        </div>
    </div>

    <!-- Midden: Canvas -->
    <div class="panel">
        <div class="panel-header" id="canvas-header">Canvas</div>
        <div class="panel-body" style="background: #f4f6f8;">
            <div id="canvas" class="canvas-area">
                <!-- Fields are rendered here -->
            </div>
        </div>
    </div>

    <!-- Rechts: Eigenschappen -->
    <div class="panel">
        <div class="panel-header">Eigenschappen</div>
        <div class="panel-body" id="properties-panel">
            <p style="color:var(--color-text-light); font-size:0.9rem;">Selecteer een veld of het formulier om eigenschappen te bewerken.</p>
        </div>
    </div>
</div>

<script>
let formsData = <?= json_encode($forms) ?>;
let activeFormIndex = <?= isset($_GET['index']) ? intval($_GET['index']) : 'null' ?>;
let isNew = <?= isset($_GET['new']) ? 'true' : 'false' ?>;
let selectedFieldIndex = null;

const paletteEl = document.getElementById('palette');
const canvasEl = document.getElementById('canvas');
const propsEl = document.getElementById('properties-panel');

// Initialize Sortable for Canvas
let sortableCanvas = new Sortable(canvasEl, {
    animation: 150,
    ghostClass: 'sortable-ghost',
    onEnd: function (evt) {
        // Update array based on new DOM order
        const form = formsData[activeFormIndex];
        const item = form.fields.splice(evt.oldIndex, 1)[0];
        form.fields.splice(evt.newIndex, 0, item);
        
        // Update selected field index if it moved
        if (selectedFieldIndex === evt.oldIndex) {
            selectedFieldIndex = evt.newIndex;
        } else if (selectedFieldIndex !== null) {
            // Need to recalculate selected index (simplified approach: just deselect)
            selectedFieldIndex = null;
        }
        renderCanvas();
    }
});

function loadForm(index) {
    activeFormIndex = parseInt(index);
    selectedFieldIndex = null;
    renderCanvas();
    renderFormProperties();
}

function createNewForm() {
    const id = prompt("Geef het formulier een unieke ID / Slug (bijv. 'contact', 'offerte'):");
    if (!id) {
        window.location.href = 'forms.php';
        return;
    }
    
    const newForm = {
        id: id,
        title: "Nieuw Formulier",
        subtitle: "",
        submit_label: "Versturen",
        admin_email: "info@grutdesigners.nl",
        success_message: "Bedankt voor je bericht!",
        fields: []
    };
    
    formsData.push(newForm);
    activeFormIndex = formsData.length - 1;
    loadForm(activeFormIndex);
}

function renderCanvas() {
    canvasEl.innerHTML = '';
    const form = formsData[activeFormIndex];
    if (!form) return;
    
    document.getElementById('canvas-header').innerText = form.title;

    form.fields.forEach((field, idx) => {
        const div = document.createElement('div');
        div.className = `field-card ${selectedFieldIndex === idx ? 'selected' : ''}`;
        div.innerHTML = `
            <div class="field-card-header">
                <span>${field.type.toUpperCase()} - Breedte: ${field.width || '100'}%</span>
                <button class="delete-field-btn" onclick="deleteField(event, ${idx})">Verwijder</button>
            </div>
            <div class="field-card-title">${field.label || 'Naamloos Veld'}</div>
            <div style="font-size:0.8rem; color:#666; margin-top:0.3rem;">name: ${field.name || 'onbekend'} ${field.required ? '<span style="color:red">*</span>' : ''}</div>
        `;
        div.onclick = () => {
            selectedFieldIndex = idx;
            renderCanvas();
            renderFieldProperties();
        };
        canvasEl.appendChild(div);
    });
}

function deleteField(e, idx) {
    e.stopPropagation();
    if (confirm("Veld verwijderen?")) {
        formsData[activeFormIndex].fields.splice(idx, 1);
        if (selectedFieldIndex === idx) selectedFieldIndex = null;
        renderCanvas();
        if (selectedFieldIndex === null) renderFormProperties();
    }
}

// Add field from palette
document.querySelectorAll('.palette-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if (activeFormIndex === null) return alert("Selecteer eerst een formulier");
        const type = btn.getAttribute('data-type');
        const newField = {
            type: type,
            name: "nieuw_" + type + "_" + Math.floor(Math.random()*1000),
            label: "Nieuw veld",
            required: false,
            width: "100"
        };
        
        if (type === 'select' || type === 'radio-pills') {
            newField.options = "optie1: Optie 1, optie2: Optie 2";
        }
        
        formsData[activeFormIndex].fields.push(newField);
        selectedFieldIndex = formsData[activeFormIndex].fields.length - 1;
        renderCanvas();
        renderFieldProperties();
    });
});

function renderFormProperties() {
    const form = formsData[activeFormIndex];
    if (!form) return;
    
    propsEl.innerHTML = `
        <h4 style="margin-top:0;">Formulier Instellingen</h4>
        
        <div class="prop-group">
            <label>ID / Slug</label>
            <input type="text" value="${form.id}" onchange="updateFormProp('id', this.value)">
        </div>
        <div class="prop-group">
            <label>Titel</label>
            <input type="text" value="${form.title}" onchange="updateFormProp('title', this.value)">
        </div>
        <div class="prop-group">
            <label>Ondertitel</label>
            <input type="text" value="${form.subtitle || ''}" onchange="updateFormProp('subtitle', this.value)">
        </div>
        <div class="prop-group">
            <label>Knop Label</label>
            <input type="text" value="${form.submit_label}" onchange="updateFormProp('submit_label', this.value)">
        </div>
        <div class="prop-group">
            <label>Admin E-mail</label>
            <input type="email" value="${form.admin_email}" onchange="updateFormProp('admin_email', this.value)">
        </div>
        <div class="prop-group">
            <label>Succes Boodschap</label>
            <textarea onchange="updateFormProp('success_message', this.value)">${form.success_message || ''}</textarea>
        </div>
    `;
}

function updateFormProp(key, value) {
    formsData[activeFormIndex][key] = value;
    if (key === 'title') renderCanvas(); // update header
}

function renderFieldProperties() {
    if (selectedFieldIndex === null) return renderFormProperties();
    
    const field = formsData[activeFormIndex].fields[selectedFieldIndex];
    
    let html = `
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem;">
            <h4 style="margin:0;">Veld Instellingen</h4>
            <button class="btn" style="padding:4px 8px; font-size:0.8rem; background:transparent; border:1px solid #ccc; color:#333;" onclick="selectedFieldIndex = null; renderCanvas(); renderFormProperties();">Terug naar formulier</button>
        </div>
        
        <div class="prop-group">
            <label>Label</label>
            <input type="text" value="${field.label || ''}" oninput="updateFieldProp('label', this.value)">
        </div>
        <div class="prop-group">
            <label>Name (Sleutel in database)</label>
            <input type="text" value="${field.name || ''}" oninput="updateFieldProp('name', this.value)">
        </div>
        
        <div class="prop-group">
            <label>Breedte</label>
            <select onchange="updateFieldProp('width', this.value)">
                <option value="100" ${field.width === '100' ? 'selected' : ''}>100% (Volledig)</option>
                <option value="50" ${field.width === '50' ? 'selected' : ''}>50% (Helft)</option>
            </select>
        </div>
        
        <div class="prop-group">
            <label><input type="checkbox" ${field.required ? 'checked' : ''} onchange="updateFieldProp('required', this.checked)"> Verplicht veld</label>
        </div>
        
        <div class="prop-group">
            <label>Autocomplete attribuut</label>
            <input type="text" value="${field.autocomplete || ''}" onchange="updateFieldProp('autocomplete', this.value)" placeholder="bijv. name, email, tel">
        </div>
    `;
    
    if (field.type === 'select' || field.type === 'radio-pills') {
        html += `
            <div class="prop-group">
                <label>Opties (Waarde: Label, gescheiden door komma)</label>
                <textarea rows="4" onchange="updateFieldProp('options', this.value)">${field.options || ''}</textarea>
            </div>
        `;
    }
    
    propsEl.innerHTML = html;
}

function updateFieldProp(key, value) {
    formsData[activeFormIndex].fields[selectedFieldIndex][key] = value;
    renderCanvas();
}

// Save
document.getElementById('save-forms-btn').addEventListener('click', async () => {
    try {
        const res = await fetch('/api/admin/form_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'}, credentials: 'same-origin',
            body: JSON.stringify({ action: 'save_forms', forms: formsData })
        });
        const json = await res.json();
        if (json.success) {
            alert('Formulieren succesvol opgeslagen!');
        } else {
            alert('Fout: ' + json.error);
        }
    } catch(e) {
        alert('Netwerk fout');
    }
});

// Init
if (isNew) {
    createNewForm();
} else if (activeFormIndex !== null && formsData[activeFormIndex]) {
    loadForm(activeFormIndex);
} else if (formsData.length > 0) {
    loadForm(0);
}
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
