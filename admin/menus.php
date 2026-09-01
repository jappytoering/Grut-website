<?php 
require_once __DIR__ . "/includes/header.php"; 
require_once __DIR__ . "/../includes/db_helper.php";

$pdo = get_db_connection();
$menus = [];

// Haal alle menu items op per menu
$stmt = $pdo->query("SELECT m.slug, i.label, i.url, i.target_blank FROM menu_items i JOIN menus m ON m.id = i.menu_id ORDER BY m.slug, i.sort_order ASC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $slug = $row['slug'];
    if (!isset($menus[$slug])) {
        $menus[$slug] = [];
    }
    // Type cast from SQLite
    $row['target_blank'] = (bool)$row['target_blank'];
    unset($row['slug']);
    $menus[$slug][] = $row;
}

if (!isset($menus['main'])) $menus['main'] = [];
if (!isset($menus['footer'])) $menus['footer'] = [];
?>
<div class="admin-header-flex" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0;">Inhoud Menu's</h1>
    <button class="btn btn-accent" id="save-menus-btn">Opslaan</button>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Main Menu -->
    <div class="card" style="padding: 1.5rem;">
        <h3 style="margin-top:0;">Hoofdmenu (Main)</h3>
        <p style="color: var(--color-text-light); font-size: 0.9rem;">Wordt bovenaan in de navigatie getoond.</p>
        
        <div id="menu-main-items" class="menu-items-container" style="display:flex; flex-direction:column; gap:1rem; margin-top:1.5rem;">
            <!-- Rendered by JS -->
        </div>
        
        <button class="btn" onclick="addMenuItem('main')" style="margin-top: 1rem; width:100%; border: 1px dashed var(--color-primary); background: transparent; color: var(--color-primary);">+ Nieuw item toevoegen</button>
    </div>

    <!-- Footer Menu -->
    <div class="card" style="padding: 1.5rem;">
        <h3 style="margin-top:0;">Footer Menu</h3>
        <p style="color: var(--color-text-light); font-size: 0.9rem;">Wordt onderaan de pagina getoond.</p>
        
        <div id="menu-footer-items" class="menu-items-container" style="display:flex; flex-direction:column; gap:1rem; margin-top:1.5rem;">
            <!-- Rendered by JS -->
        </div>
        
        <button class="btn" onclick="addMenuItem('footer')" style="margin-top: 1rem; width:100%; border: 1px dashed var(--color-primary); background: transparent; color: var(--color-primary);">+ Nieuw item toevoegen</button>
    </div>
</div>

<script>
let menusData = <?= json_encode($menus) ?>;

function renderMenus() {
    ['main', 'footer'].forEach(menuId => {
        const container = document.getElementById(`menu-${menuId}-items`);
        container.innerHTML = '';
        
        menusData[menuId].forEach((item, index) => {
            const html = `
                <div class="menu-item-editor" style="background: #f8f9fa; border: 1px solid var(--color-border); padding: 1rem; border-radius: 8px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom: 0.5rem;">
                        <strong style="font-size: 0.9rem;">Item #${index + 1}</strong>
                        <button onclick="removeMenuItem('${menuId}', ${index})" style="background:none; border:none; color:red; cursor:pointer;">Verwijder</button>
                    </div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.5rem; margin-bottom: 0.5rem;">
                        <div>
                            <label style="font-size:0.8rem; display:block;">Label (Tekst)</label>
                            <input type="text" value="${item.label}" onchange="updateMenuField('${menuId}', ${index}, 'label', this.value)" style="width:100%; padding:0.4rem; border:1px solid #ccc; border-radius:4px; box-sizing:border-box;">
                        </div>
                        <div>
                            <label style="font-size:0.8rem; display:block;">URL / Slug</label>
                            <input type="text" value="${item.url}" onchange="updateMenuField('${menuId}', ${index}, 'url', this.value)" style="width:100%; padding:0.4rem; border:1px solid #ccc; border-radius:4px; box-sizing:border-box;">
                        </div>
                    </div>
                    <div>
                        <label style="font-size:0.8rem; display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                            <input type="checkbox" ${item.target_blank ? 'checked' : ''} onchange="updateMenuField('${menuId}', ${index}, 'target_blank', this.checked)">
                            Open in nieuw tabblad (target="_blank")
                        </label>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
    });
}

function updateMenuField(menuId, index, field, value) {
    menusData[menuId][index][field] = value;
}

function addMenuItem(menuId) {
    menusData[menuId].push({ label: 'Nieuw Item', url: '/', target_blank: false });
    renderMenus();
}

function removeMenuItem(menuId, index) {
    if(confirm('Zeker weten?')) {
        menusData[menuId].splice(index, 1);
        renderMenus();
    }
}

document.getElementById('save-menus-btn').addEventListener('click', async () => {
    try {
        const res = await fetch('/api/admin/menu_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ action: 'save_menus', menus: menusData })
        });
        const json = await res.json();
        if (json.success) {
            alert('Menu\'s succesvol opgeslagen!');
        } else {
            alert('Fout: ' + json.error);
        }
    } catch(e) {
        alert('Netwerk fout');
    }
});

// Init
renderMenus();
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
