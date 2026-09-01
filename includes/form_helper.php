<?php

require_once __DIR__ . '/db_helper.php';
/**
 * Form Renderer & Template Helper
 * 
 * Beheert de rendering van dynamische contact- en lead-intake blokken.
 */

function render_cta_block($form_identifier, $config = []) {
    // $form_identifier can be an ID (int) or slug (string)
    $preset = null;
    $fields = [];
    
    try {
        $pdo = get_cms_connection();
if (is_numeric($form_identifier)) {
            $stmt = $pdo->prepare("SELECT * FROM forms WHERE id = ?");
        } else {
            $stmt = $pdo->prepare("SELECT * FROM forms WHERE slug = ?");
        }
        $stmt->execute([$form_identifier]);
        $preset = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($preset) {
            $stmt = $pdo->prepare("SELECT * FROM form_fields WHERE form_id = ? ORDER BY sort_order ASC");
            $stmt->execute([$preset['id']]);
            $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        return "<!-- Error database verbinding: " . htmlspecialchars($e->getMessage()) . " -->";
    }
    
    if (!$preset) {
        return "<!-- Ongeldige formulier identifier: " . htmlspecialchars($form_identifier) . " -->";
    }

    // 2. Pas configuratie-overrides toe (vanuit CMS pagina properties bijv.)
    $success_message = $config['success_message'] ?? ($preset['success_message'] ?? 'We hebben je bericht goed ontvangen en nemen spoedig contact met je op.');
    $title       = $config['title'] ?? ($preset['title'] ?? '');
    $subtitle    = $config['subtitle'] ?? ($preset['subtitle'] ?? '');
    $button_text = $config['button_text'] ?? ($preset['submit_label'] ?? 'Versturen');
    $preset_id   = $preset['slug']; // use slug as preset_id for frontend script compatibility
    
    $custom_header_html = $config['custom_header_html'] ?? null;
    $custom_success_html = $config['custom_success_html'] ?? null;

    // Helper voor radio/select options (komma gescheiden string naar array)
    $parse_options = function($options_string) {
        $arr = [];
        if (!$options_string) return $arr;
        $parts = explode(',', $options_string);
        foreach ($parts as $part) {
            $kv = explode(':', $part, 2);
            if (count($kv) == 2) {
                $arr[trim($kv[0])] = trim($kv[1]);
            } else {
                $arr[trim($kv[0])] = trim($kv[0]);
            }
        }
        return $arr;
    };

    ob_start();
    ?>
    <div class="cta-block" data-preset="<?= htmlspecialchars($preset_id) ?>">
        <div class="cta-header">
            <?php if ($custom_header_html): ?>
                <?= $custom_header_html ?>
            <?php else: ?>
                <?php if ($title): ?>
                    <h2 class="cta-title"><?= htmlspecialchars($title) ?></h2>
                <?php endif; ?>
                <?php if ($subtitle): ?>
                    <p class="cta-subtitle"><?= htmlspecialchars($subtitle) ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <form class="cta-form" data-contact-form action="/api/submit-contact.php" method="POST" novalidate>
            <!-- Verborgen metadata velden -->
            <input type="hidden" name="preset_id" value="<?= htmlspecialchars($preset_id) ?>">
            
            <!-- Bot-Protectie (Honeypot) -->
            <div style="display:none;" aria-hidden="true">
                <label for="website_hp_<?= htmlspecialchars($preset_id) ?>">Laat dit veld leeg</label>
                <input type="text" id="website_hp_<?= htmlspecialchars($preset_id) ?>" name="website_hp" tabindex="-1" autocomplete="off">
            </div>

            <div class="cta-form-fields" style="display: flex; flex-wrap: wrap; gap: 1rem;">
                <?php 
                $bottom_fields = [];
                foreach ($fields as $field): 
                    $field_key = $field['name'];
                    
                    if (($field['type'] === 'checkbox') && (isset($field['position']) && $field['position'] === 'bottom')) {
                        // Legacy support for specific positions
                        $bottom_fields[] = $field;
                        continue;
                    }
                    
                    $field_id = "field_{$preset_id}_{$field_key}";
                    $required_attr = !empty($field['required']) ? 'required' : '';
                    $required_mark = !empty($field['required']) ? '<span class="required-mark">*</span>' : '';
                    $width = $field['width'] ?? '100';
                    $flex_basis = $width == '50' ? 'calc(50% - 0.5rem)' : '100%';
                    ?>
                    
                    <div class="form-group form-group-<?= htmlspecialchars($field['type']) ?> form-group-field-<?= htmlspecialchars($field_key) ?>" style="flex: 0 0 <?= $flex_basis ?>; max-width: <?= $flex_basis ?>;">
                        <?php if ($field['type'] !== 'radio-pills' && $field['type'] !== 'checkbox'): ?>
                            <label for="<?= $field_id ?>"><?= htmlspecialchars($field['label']) ?> <?= $required_mark ?></label>
                        <?php elseif ($field['type'] === 'radio-pills'): ?>
                            <fieldset style="border:none; padding:0; margin:0;">
                                <legend style="font-weight:600; margin-bottom:0.5rem;"><?= htmlspecialchars($field['label']) ?> <?= $required_mark ?></legend>
                        <?php endif; ?>

                        <?php if ($field['type'] === 'textarea'): ?>
                            <?php $maxlength_attr = !empty($field['maxlength']) ? 'maxlength="' . (int)$field['maxlength'] . '"' : ''; ?>
                            <textarea id="<?= $field_id ?>" name="<?= htmlspecialchars($field_key) ?>" <?= $required_attr ?> <?= $maxlength_attr ?> rows="4" style="width:100%; padding:0.8rem; border:1px solid var(--color-border); border-radius:4px;" oninput="if(this.maxLength > 0) this.nextElementSibling.textContent = this.value.length + ' / ' + this.maxLength + ' tekens';"></textarea>
                            <?php if (!empty($field['maxlength'])): ?>
                                <div class="char-counter" style="font-size:0.8rem; color:gray; text-align:right;">0 / <?= (int)$field['maxlength'] ?> tekens</div>
                            <?php endif; ?>
                        
                        <?php elseif ($field['type'] === 'select'): ?>
                            <select id="<?= $field_id ?>" name="<?= htmlspecialchars($field_key) ?>" <?= $required_attr ?> style="width:100%; padding:0.8rem; border:1px solid var(--color-border); border-radius:4px;">
                                <?php foreach ($parse_options($field['options'] ?? '') as $val => $label): ?>
                                    <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>

                        <?php elseif ($field['type'] === 'radio-pills'): ?>
                            <div class="radio-pills-container" style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                                <?php foreach ($parse_options($field['options'] ?? '') as $val => $label): ?>
                                    <label class="radio-pill" style="display:flex; align-items:center; background:#f4f6f8; padding:0.6rem 1rem; border-radius:20px; cursor:pointer;">
                                        <input type="radio" name="<?= htmlspecialchars($field_key) ?>" value="<?= htmlspecialchars($val) ?>" <?= $required_attr ?> style="margin-right:0.5rem;">
                                        <span class="pill-label"><?= htmlspecialchars($label) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            </fieldset>

                        <?php elseif ($field['type'] === 'checkbox'): ?>
                            <label class="checkbox-label" style="display:flex; align-items:center; cursor:pointer; margin-top:1.5rem;">
                                <input type="checkbox" id="<?= $field_id ?>" name="<?= htmlspecialchars($field_key) ?>" value="1" <?= $required_attr ?> style="margin-right:0.5rem; width:auto;">
                                <span><?= htmlspecialchars($field['label']) ?> <?= $required_mark ?></span>
                            </label>

                        <?php else: ?>
                            <input type="<?= htmlspecialchars($field['type']) ?>" 
                                   id="<?= $field_id ?>" 
                                   name="<?= htmlspecialchars($field_key) ?>" 
                                   autocomplete="<?= htmlspecialchars($field['autocomplete'] ?? '') ?>" 
                                   <?= $required_attr ?>
                                   style="width:100%; padding:0.8rem; border:1px solid var(--color-border); border-radius:4px;">
                        <?php endif; ?>
                        
                        <!-- Plek voor inline validatiefout -->
                        <div class="error-message" data-error-for="<?= htmlspecialchars($field_key) ?>" style="color:red; font-size:0.8rem; margin-top:0.3rem;"></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Foutmelding op formulier niveau -->
            <div class="form-error-message" style="display:none; color:red; margin-bottom:1rem; padding:1rem; background:#fee; border-radius:4px;"></div>

            <div class="cta-form-actions" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary cta-submit-btn" style="background:var(--color-primary); color:white; padding:1rem 2rem; border-radius:6px; border:none; font-size:1rem; font-weight:bold; cursor:pointer;">
                    <span class="btn-text"><?= htmlspecialchars($button_text) ?></span>
                    <span class="btn-spinner" style="display:none;" aria-hidden="true">⏳</span>
                </button>
            </div>
            
            <?php if (!empty($bottom_fields)): ?>
                <div class="cta-form-bottom-fields" style="margin-top: 24px;">
                    <?php foreach ($bottom_fields as $field): ?>
                        <?php 
                        $field_id = "field_{$preset_id}_{$field['name']}";
                        $required_attr = !empty($field['required']) ? 'required' : '';
                        $required_mark = !empty($field['required']) ? '<span class="required-mark">*</span>' : '';
                        ?>
                        <div class="form-group form-group-<?= htmlspecialchars($field['type']) ?> form-group-field-<?= htmlspecialchars($field['name']) ?>">
                            <?php if ($field['type'] === 'checkbox'): ?>
                                <label class="checkbox-label" style="display:flex; align-items:center; cursor:pointer;">
                                    <input type="checkbox" id="<?= $field_id ?>" name="<?= htmlspecialchars($field['name']) ?>" value="1" <?= $required_attr ?> style="margin-right:0.5rem; width:auto;">
                                    <span><?= htmlspecialchars($field['label']) ?> <?= $required_mark ?></span>
                                </label>
                            <?php endif; ?>
                            <div class="error-message" data-error-for="<?= htmlspecialchars($field['name']) ?>" style="color:red; font-size:0.8rem; margin-top:0.3rem;"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Succes bedankje container -->
            <div class="cta-success-message" style="display:none; margin-top:2rem; padding:2rem; background:#e8f5e9; border-radius:8px; text-align:center;" aria-live="polite">
                <?php if ($custom_success_html): ?>
                    <?= $custom_success_html ?>
                <?php else: ?>
                    <h3 style="margin-top:0; color:#2e7d32;">Bedankt!</h3>
                    <p style="margin-bottom:0; color:#1b5e20;"><?= htmlspecialchars($success_message) ?></p>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
