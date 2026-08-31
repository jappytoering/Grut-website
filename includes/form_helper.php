<?php
/**
 * Form Renderer & Template Helper
 * 
 * Beheert de rendering van contact- en lead-intake blokken.
 */

function render_cta_block($preset_id, $config = []) {
    // Standaard configuratie per preset
    $presets = [
        'quick-connect' => [
            'title'       => 'Snel in contact',
            'subtitle'    => 'Laat je gegevens achter en we nemen contact met je op.',
            'button_text' => 'Verstuur bericht',
            'fields'      => [
                'name'    => ['label' => 'Naam', 'type' => 'text', 'autocomplete' => 'name', 'required' => true],
                'email'   => ['label' => 'E-mailadres', 'type' => 'email', 'autocomplete' => 'email', 'required' => true],
                'message' => ['label' => 'Bericht', 'type' => 'textarea', 'required' => true],
            ],
            'field_order' => ['name', 'email', 'message'],
        ],
        'project-intake' => [
            'title'       => 'Start een project',
            'subtitle'    => 'Vertel ons over je uitdaging.',
            'button_text' => 'Bespreek je project',
            'fields'      => [
                'name'     => ['label' => 'Naam', 'type' => 'text', 'autocomplete' => 'name', 'required' => true],
                'company'  => ['label' => 'Bedrijfsnaam', 'type' => 'text', 'autocomplete' => 'organization', 'required' => false],
                'email'    => ['label' => 'E-mailadres', 'type' => 'email', 'autocomplete' => 'email', 'required' => true],
                'phone'    => ['label' => 'Telefoonnummer', 'type' => 'tel', 'autocomplete' => 'tel', 'required' => false],
                'service'  => [
                    'label'   => 'Type Dienst',
                    'type'    => 'radio-pills',
                    'options' => [
                        'ux-ui'       => 'UX/UI Design',
                        'webdev'      => 'Webdevelopment',
                        'design-sys'  => 'Design System',
                        'consultancy' => 'Consultancy'
                    ],
                    'required' => true
                ],
                'budget'   => [
                    'label'   => 'Indicatief Budget',
                    'type'    => 'select',
                    'options' => [
                        ''          => 'Kies een budget...',
                        '5k-10k'    => '€5.000 - €10.000',
                        '10k-25k'   => '€10.000 - €25.000',
                        '25k-50k'   => '€25.000 - €50.000',
                        '50k+'      => '€50.000+'
                    ],
                    'required' => false
                ],
                'message'  => ['label' => 'Toelichting project', 'type' => 'textarea', 'required' => true],
            ],
            'field_order' => ['name', 'company', 'email', 'phone', 'service', 'budget', 'message'],
        ],
        'prototype-sprint' => [
            'title'       => 'Start jouw Prototype Sprint',
            'subtitle'    => 'Laat je gegevens achter en we bespreken je casus.',
            'button_text' => 'Versturen',
            'success_message' => 'Bedankt voor je aanvraag, we duiken in je casus!',
            'fields'      => [
                'first_name'        => ['label' => 'Voornaam', 'type' => 'text', 'autocomplete' => 'given-name', 'required' => true],
                'last_name'         => ['label' => 'Achternaam', 'type' => 'text', 'autocomplete' => 'family-name', 'required' => true],
                'email'             => ['label' => 'E-mailadres', 'type' => 'email', 'autocomplete' => 'email', 'required' => true],
                'phone'             => ['label' => 'Telefoonnummer', 'type' => 'tel', 'autocomplete' => 'tel', 'required' => false],
                'case_description'  => ['label' => 'Casus', 'type' => 'textarea', 'required' => false, 'maxlength' => 200],
                'newsletter_opt_in' => ['label' => 'Ik wil op de hoogte blijven van nieuws', 'type' => 'checkbox', 'required' => false, 'position' => 'bottom'],
            ],
            'field_order' => ['first_name', 'last_name', 'email', 'phone', 'case_description', 'newsletter_opt_in'],
        ],
    ];

    if (!isset($presets[$preset_id])) {
        return "<!-- Ongeldige formulier preset: " . htmlspecialchars($preset_id) . " -->";
    }

    $preset = $presets[$preset_id];
    
    // Pas configuratie-overrides toe
    $success_message = $config['success_message'] ?? ($preset['success_message'] ?? 'We hebben je bericht goed ontvangen en nemen spoedig contact met je op.');
    $title       = $config['title'] ?? $preset['title'];
    $subtitle    = $config['subtitle'] ?? $preset['subtitle'];
    $button_text = $config['button_text'] ?? $preset['button_text'];
    $field_order = $config['field_order'] ?? $preset['field_order'];
    $custom_header_html = $config['custom_header_html'] ?? ($preset['custom_header_html'] ?? null);
    $custom_success_html = $config['custom_success_html'] ?? ($preset['custom_success_html'] ?? null);
    $fields      = $preset['fields'];

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
                <label for="website_hp_<?= $preset_id ?>">Laat dit veld leeg</label>
                <input type="text" id="website_hp_<?= $preset_id ?>" name="website_hp" tabindex="-1" autocomplete="off">
            </div>

            <div class="cta-form-fields">
                <?php 
                $bottom_fields = [];
                foreach ($field_order as $field_key): 
                    if (!isset($fields[$field_key])) continue; 
                    $field = $fields[$field_key];
                    
                    if (isset($field['position']) && $field['position'] === 'bottom') {
                        $bottom_fields[$field_key] = $field;
                        continue;
                    }
                    
                    $field_id = "field_{$preset_id}_{$field_key}";
                    $required_attr = $field['required'] ? 'required' : '';
                    $required_mark = $field['required'] ? '<span class="required-mark">*</span>' : '';
                    ?>
                    
                    <div class="form-group form-group-<?= htmlspecialchars($field['type']) ?> form-group-field-<?= htmlspecialchars($field_key) ?>">
                        <?php if ($field['type'] !== 'radio-pills' && $field['type'] !== 'checkbox'): ?>
                            <label for="<?= $field_id ?>"><?= htmlspecialchars($field['label']) ?> <?= $required_mark ?></label>
                        <?php elseif ($field['type'] === 'radio-pills'): ?>
                            <fieldset>
                                <legend><?= htmlspecialchars($field['label']) ?> <?= $required_mark ?></legend>
                        <?php endif; ?>

                        <?php if ($field['type'] === 'textarea'): ?>
                            <?php $maxlength_attr = isset($field['maxlength']) ? 'maxlength="' . (int)$field['maxlength'] . '"' : ''; ?>
                            <textarea id="<?= $field_id ?>" name="<?= htmlspecialchars($field_key) ?>" <?= $required_attr ?> <?= $maxlength_attr ?> rows="4" oninput="if(this.maxLength > 0) this.nextElementSibling.textContent = this.value.length + ' / ' + this.maxLength + ' tekens';"></textarea>
                            <?php if (isset($field['maxlength'])): ?>
                                <div class="char-counter">0 / <?= (int)$field['maxlength'] ?> tekens</div>
                            <?php endif; ?>
                        
                        <?php elseif ($field['type'] === 'select'): ?>
                            <select id="<?= $field_id ?>" name="<?= htmlspecialchars($field_key) ?>" <?= $required_attr ?>>
                                <?php foreach ($field['options'] as $val => $label): ?>
                                    <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>

                        <?php elseif ($field['type'] === 'radio-pills'): ?>
                            <div class="radio-pills-container">
                                <?php foreach ($field['options'] as $val => $label): ?>
                                    <label class="radio-pill">
                                        <input type="radio" name="<?= htmlspecialchars($field_key) ?>" value="<?= htmlspecialchars($val) ?>" <?= $required_attr ?>>
                                        <span class="pill-label"><?= htmlspecialchars($label) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            </fieldset>

                        <?php elseif ($field['type'] === 'checkbox'): ?>
                            <label class="checkbox-label">
                                <input type="checkbox" id="<?= $field_id ?>" name="<?= htmlspecialchars($field_key) ?>" value="1" <?= $required_attr ?>>
                                <span><?= htmlspecialchars($field['label']) ?> <?= $required_mark ?></span>
                            </label>

                        <?php else: ?>
                            <input type="<?= htmlspecialchars($field['type']) ?>" 
                                   id="<?= $field_id ?>" 
                                   name="<?= htmlspecialchars($field_key) ?>" 
                                   autocomplete="<?= htmlspecialchars($field['autocomplete'] ?? '') ?>" 
                                   <?= $required_attr ?>>
                        <?php endif; ?>
                        
                        <!-- Plek voor inline validatiefout -->
                        <div class="error-message" data-error-for="<?= htmlspecialchars($field_key) ?>"></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Foutmelding op formulier niveau -->
            <div class="form-error-message" style="display:none;"></div>

            <div class="cta-form-actions">
                <button type="submit" class="btn btn-primary cta-submit-btn">
                    <span class="btn-text"><?= htmlspecialchars($button_text) ?></span>
                    <span class="btn-spinner" style="display:none;" aria-hidden="true">⏳</span>
                </button>
            </div>
            
            <?php if (!empty($bottom_fields)): ?>
                <div class="cta-form-bottom-fields" style="margin-top: 24px;">
                    <?php foreach ($bottom_fields as $field_key => $field): ?>
                        <?php 
                        $field_id = "field_{$preset_id}_{$field_key}";
                        $required_attr = $field['required'] ? 'required' : '';
                        $required_mark = $field['required'] ? '<span class="required-mark">*</span>' : '';
                        ?>
                        <div class="form-group form-group-<?= htmlspecialchars($field['type']) ?> form-group-field-<?= htmlspecialchars($field_key) ?>">
                            <?php if ($field['type'] === 'checkbox'): ?>
                                <label class="checkbox-label">
                                    <input type="checkbox" id="<?= $field_id ?>" name="<?= htmlspecialchars($field_key) ?>" value="1" <?= $required_attr ?>>
                                    <span><?= htmlspecialchars($field['label']) ?> <?= $required_mark ?></span>
                                </label>
                            <?php endif; ?>
                            <div class="error-message" data-error-for="<?= htmlspecialchars($field_key) ?>"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Succes bedankje container -->
            <div class="cta-success-message" style="display:none;" aria-live="polite">
                <?php if ($custom_success_html): ?>
                    <?= $custom_success_html ?>
                <?php else: ?>
                    <h3>Bedankt!</h3>
                    <p><?= htmlspecialchars($success_message) ?></p>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
