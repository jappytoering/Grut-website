<?php
require_once __DIR__ . '/includes/header.php';

$dbPath = __DIR__ . '/../storage/content.sqlite';
$keys = [];
$locales = ['nl', 'en', 'fy'];

try {
    if (file_exists($dbPath)) {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Haal alle keys op
        $stmt = $pdo->query("SELECT id, key_name FROM content_keys ORDER BY key_name ASC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $key_id = $row['id'];
            $key_name = $row['key_name'];
            $keys[$key_name] = ['nl' => '', 'en' => '', 'fy' => ''];
            
            // Haal vertalingen op voor deze key
            $tStmt = $pdo->prepare("SELECT locale, value FROM content_translations WHERE key_id = ?");
            $tStmt->execute([$key_id]);
            while ($tRow = $tStmt->fetch(PDO::FETCH_ASSOC)) {
                $keys[$key_name][$tRow['locale']] = $tRow['value'];
            }
        }
    }
} catch (Exception $e) {
    echo "Fout: " . htmlspecialchars($e->getMessage());
}

?>

<style>
    .content-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }
    .content-table th, .content-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid var(--color-border);
        vertical-align: top;
    }
    .content-table th {
        background: #f8fafc;
        font-weight: 600;
        color: var(--color-text-light);
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .content-key {
        font-family: monospace;
        color: var(--color-primary);
        font-size: 13px;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 4px;
        word-break: break-all;
    }
    .content-input {
        width: 100%;
        min-height: 40px;
        padding: 10px;
        border: 1px solid var(--color-border);
        border-radius: 6px;
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        resize: vertical;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .content-input:focus {
        outline: none;
        border-color: var(--color-accent);
    }
    .save-indicator {
        font-size: 12px;
        color: #10b981; /* green */
        font-weight: bold;
        opacity: 0;
        transition: opacity 0.3s;
        display: block;
        margin-top: 4px;
    }
    .save-indicator.show {
        opacity: 1;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Teksten & Content</h1>
</div>

<div class="card">
    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 25%">Sleutel (Key)</th>
                <th style="width: 25%">Nederlands (nl)</th>
                <th style="width: 25%">Engels (en)</th>
                <th style="width: 25%">Fries (fy)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($keys as $key_name => $translations): ?>
            <tr>
                <td>
                    <div class="content-key"><?php echo htmlspecialchars($key_name); ?></div>
                </td>
                <?php foreach (['nl', 'en', 'fy'] as $locale): ?>
                <td>
                    <textarea class="content-input" 
                              data-key="<?php echo htmlspecialchars($key_name); ?>" 
                              data-locale="<?php echo $locale; ?>"
                              placeholder="Vul vertaling in..."><?php echo htmlspecialchars($translations[$locale]); ?></textarea>
                    <span class="save-indicator">Opgeslagen ✓</span>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
// Auto-save functionaliteit
let typingTimer;
const doneTypingInterval = 800; // Wacht 800ms na de laatste aanslag

document.querySelectorAll('.content-input').forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => saveContent(this), doneTypingInterval);
    });
});

function saveContent(element) {
    const key = element.getAttribute('data-key');
    const locale = element.getAttribute('data-locale');
    const value = element.value;
    const indicator = element.nextElementSibling;

    fetch('/api/admin/save_content.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            key_name: key,
            locale: locale,
            value: value
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            indicator.classList.add('show');
            setTimeout(() => {
                indicator.classList.remove('show');
            }, 2000);
        } else {
            alert("Fout bij opslaan: " + (data.error || "Onbekende fout"));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Kan geen verbinding maken met de server.");
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
