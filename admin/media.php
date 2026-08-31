<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../includes/media_helper.php';

if (isset($_GET['delete']) && AuthEngine::has_role('super_admin')) {
    $del_id = $_GET['delete'];
    delete_asset($del_id);
    header("Location: media.php");
    exit;
}

$assets = [];
try {
    $dbPath = __DIR__ . '/../storage/content.sqlite';
    if (file_exists($dbPath)) {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT * FROM media_assets ORDER BY created_at DESC");
        $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {}

?>

<style>
    .gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    .media-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid var(--color-border);
        display: flex;
        flex-direction: column;
    }
    .media-preview {
        height: 150px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .media-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .media-info {
        padding: 12px;
        font-size: 13px;
        flex-grow: 1;
    }
    .media-name {
        font-weight: 600;
        margin-bottom: 4px;
        word-break: break-all;
    }
    .media-meta {
        color: var(--color-text-light);
        font-size: 11px;
        margin-bottom: 10px;
    }
    .upload-box {
        border: 2px dashed var(--color-primary);
        padding: 40px;
        text-align: center;
        border-radius: 12px;
        background: #f8fafc;
        cursor: pointer;
        transition: background 0.2s;
    }
    .upload-box:hover {
        background: #f1f5f9;
    }
    .btn-danger {
        color: #ef4444;
        text-decoration: none;
        font-weight: bold;
        font-size: 12px;
    }
    .btn-danger:hover {
        text-decoration: underline;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Media Hub</h1>
</div>

<div class="card" style="padding: 30px; margin-bottom: 40px;">
    <h3 style="margin-top:0;">Nieuwe afbeelding uploaden</h3>
    
    <div class="upload-box" id="dropzone">
        <p style="margin: 0; font-weight: 600; color: var(--color-primary);">Sleep een afbeelding hierheen of klik om te bladeren</p>
        <p style="margin: 5px 0 0; font-size: 13px; color: var(--color-text-light);">Ondersteunt JPG, PNG, WEBP. Wordt automatisch geoptimaliseerd.</p>
        <input type="file" id="fileInput" style="display: none;" accept="image/*">
    </div>
    
    <div id="uploadProgress" style="display: none; margin-top: 15px; font-weight: bold; color: var(--color-accent);">
        Uploaden...
    </div>
</div>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h3>Bibliotheek (<span id="asset-count"><?php echo count($assets); ?></span> items)</h3>
    <input type="text" id="tag-filter" placeholder="Filter op tags..." style="padding: 0.5rem; border-radius: 4px; border: 1px solid #ccc; min-width: 250px;">
</div>

<div class="gallery" id="media-gallery">
    <?php foreach ($assets as $asset): ?>
        <div class="media-card" data-tags="<?php echo htmlspecialchars(strtolower($asset['tags'] ?? '')); ?>">
            <div class="media-preview">
                <?php echo render_image($asset['asset_id'], ['alt' => $asset['alt_text'] ?? '', 'loading' => 'lazy']); ?>
            </div>
            <div class="media-info">
                <div class="media-name"><?php echo htmlspecialchars($asset['original_filename']); ?></div>
                <div class="media-meta">ID: <?php echo htmlspecialchars($asset['asset_id']); ?></div>
                
                <div style="margin-bottom: 10px;">
                    <input type="text" value="<?php echo htmlspecialchars($asset['tags'] ?? ''); ?>" 
                           placeholder="Tags (komma gescheiden)" 
                           style="width:100%; box-sizing:border-box; padding:4px; font-size:11px; border:1px solid #ccc; border-radius:4px;"
                           onchange="updateTags('<?php echo $asset['asset_id']; ?>', this.value)">
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <button type="button" class="btn btn-outline" style="padding: 2px 6px; font-size: 11px;" onclick="copyPath('<?php echo htmlspecialchars($asset['asset_id']); ?>')">Kopieer Pad</button>
                    <?php if (AuthEngine::has_role('super_admin')): ?>
                        <a href="?delete=<?php echo $asset['asset_id']; ?>" class="btn-danger" onclick="return confirm('Zeker weten? Dit verwijdert de foto permanent.');">Verwijderen</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');
const progress = document.getElementById('uploadProgress');

dropzone.addEventListener('click', () => fileInput.click());

dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.style.background = '#e2e8f0';
});

dropzone.addEventListener('dragleave', () => {
    dropzone.style.background = '#f8fafc';
});

dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.style.background = '#f8fafc';
    if (e.dataTransfer.files.length) {
        uploadFile(e.dataTransfer.files[0]);
    }
});

fileInput.addEventListener('change', function() {
    if (this.files.length) {
        uploadFile(this.files[0]);
    }
});

function uploadFile(file) {
    if (!file.type.startsWith('image/')) {
        alert('Alleen afbeeldingen zijn toegestaan.');
        return;
    }

    const formData = new FormData();
    formData.append('file', file);
    
    progress.style.display = 'block';

    fetch('/api/admin/upload_media.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        progress.style.display = 'none';
        if(data.success) {
            window.location.reload(); // Herlaad om nieuwe foto te tonen
        } else {
            alert("Fout bij uploaden: " + (data.error || "Onbekende fout"));
        }
    })
    .catch(error => {
        progress.style.display = 'none';
        console.error('Error:', error);
        alert("Upload mislukt.");
    });
}

function updateTags(assetId, tags) {
    fetch('/api/admin/media_api.php?action=update_tags', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ asset_id: assetId, tags: tags })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) alert('Fout bij opslaan tags: ' + (data.error || 'Onbekend'));
    });
}

function copyPath(assetId) {
    // For now we just copy the assetId since that's what render_image expects
    navigator.clipboard.writeText(assetId).then(() => {
        alert("ID/Pad gekopieerd: " + assetId);
    });
}

// Filter logic
document.getElementById('tag-filter').addEventListener('input', function(e) {
    const filterText = e.target.value.toLowerCase().trim();
    const cards = document.querySelectorAll('.media-card');
    let count = 0;
    
    cards.forEach(card => {
        const tags = card.dataset.tags || '';
        if (filterText === '' || tags.includes(filterText)) {
            card.style.display = 'flex';
            count++;
        } else {
            card.style.display = 'none';
        }
    });
    
    document.getElementById('asset-count').innerText = count;
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
