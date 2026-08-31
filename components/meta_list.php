<?php
/**
 * Component: Meta List (voor overlays)
 */
$title = $content['title'] ?? '';
$items = [];
for($i = 1; $i <= 4; $i++) {
    if (!empty($content["label_$i"]) && !empty($content["value_$i"])) {
        $items[] = ['label' => $content["label_$i"], 'value' => $content["value_$i"]];
    }
}
?>
<?php if($title): ?>
<h4 style="margin-bottom: 0.5rem; color: rgba(255, 255, 255, 0.9); font-size: 1.4rem; font-weight: 600;"><?= htmlspecialchars($title) ?></h4>
<?php endif; ?>
<?php if(!empty($items)): ?>
<div class="modal-meta-list" style="margin-top: 0.5rem; margin-bottom: 2rem;">
    <?php foreach($items as $item): ?>
    <div class="modal-meta-item">
        <span class="modal-meta-label"><?= htmlspecialchars($item['label']) ?></span>
        <span class="modal-meta-value"><?= htmlspecialchars($item['value']) ?></span>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
