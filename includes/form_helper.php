<?php
// form_helper.php
$FORM_PRESETS = [
    'algemeen-contact' => [
        'id' => 'algemeen-contact',
        'title' => 'Neem contact op',
        'fields' => [
            ['name' => 'name', 'type' => 'text', 'label' => 'Naam', 'required' => true],
            ['name' => 'email', 'type' => 'email', 'label' => 'E-mailadres', 'required' => true],
            ['name' => 'company', 'type' => 'text', 'label' => 'Bedrijf (optioneel)', 'required' => false],
            ['name' => 'message', 'type' => 'textarea', 'label' => 'Bericht', 'required' => true]
        ],
        'submit_text' => 'Verstuur bericht'
    ],
    'prototype-sprint' => [
        'id' => 'prototype-sprint',
        'title' => 'Prototype Sprint Aanvragen',
        'fields' => [
            ['name' => 'name', 'type' => 'text', 'label' => 'Naam', 'required' => true],
            ['name' => 'email', 'type' => 'email', 'label' => 'E-mailadres', 'required' => true],
            ['name' => 'phone', 'type' => 'tel', 'label' => 'Telefoonnummer (optioneel)', 'required' => false],
            ['name' => 'message', 'type' => 'textarea', 'label' => 'Waar gaat het over?', 'required' => true]
        ],
        'submit_text' => 'Plan een kennismaking'
    ]
];

function render_grut_form(string $presetId): string {
    global $FORM_PRESETS;
    if (!isset($FORM_PRESETS[$presetId])) {
        return "<p>Formulier niet gevonden.</p>";
    }
    
    $form = $FORM_PRESETS[$presetId];
    
    $html = '<div class="contact-form-container">';
    $html .= '<form data-contact-form="true" id="form-'.$form['id'].'" method="POST" action="/api/submit-contact.php">';
    
    // Hidden fields for backend logic
    $html .= '<input type="hidden" name="preset_id" value="'.htmlspecialchars($form['id']).'">';
    $html .= '<input type="hidden" name="website_hp" value="">';
    $html .= '<input type="hidden" name="browser_timestamp" class="bt_field" value="">';
    
    // Global error message wrapper
    $html .= '<div class="form-error-message" style="display:none; color: #ff4b4b; background: rgba(255, 75, 75, 0.1); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 500;"></div>';
    
    // Form fields wrapper
    $html .= '<div class="cta-form-fields">';
    foreach ($form['fields'] as $field) {
        $req = $field['required'] ? 'required' : '';
        $html .= '<div class="form-group" style="margin-bottom: 1.5rem;">';
        $html .= '<label style="display: block; margin-bottom: 0.5rem; color: #fff; font-weight: 500;">'.htmlspecialchars($field['label']).'</label>';
        
        $inputStyle = 'width: 100%; padding: 12px 16px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; color: #fff; font-family: inherit; font-size: 1rem;';
        
        if ($field['type'] === 'textarea') {
            $html .= '<textarea name="'.htmlspecialchars($field['name']).'" '.$req.' style="'.$inputStyle.' min-height: 120px;"></textarea>';
        } else {
            $html .= '<input type="'.htmlspecialchars($field['type']).'" name="'.htmlspecialchars($field['name']).'" '.$req.' style="'.$inputStyle.'">';
        }
        $html .= '<div class="error-message" style="color: #ff4b4b; font-size: 0.85rem; margin-top: 0.25rem;"></div>';
        $html .= '</div>';
    }
    $html .= '</div>'; // End cta-form-fields
    
    // Actions wrapper
    $html .= '<div class="cta-form-actions">';
    $html .= '<button type="submit" class="cta-submit-btn" style="background: var(--color-yellow, #FFD028); color: #000; border: none; padding: 14px 28px; border-radius: 99px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: opacity 0.2s ease;">';
    $html .= '<span class="btn-text">'.htmlspecialchars($form['submit_text']).'</span>';
    $html .= '<span class="btn-spinner" style="display: none;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2v4M12 18v4M2 12h4M18 12h4" stroke-opacity="0.5"></path></svg></span>';
    $html .= '</button>';
    $html .= '</div>';
    
    // Success message (hidden initially)
    $html .= '<div class="cta-success-message" style="display:none; text-align: center; padding: 3rem 1rem;">';
    $html .= '<div style="width: 64px; height: 64px; background: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">';
    $html .= '<svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';
    $html .= '</div>';
    $html .= '<h3 style="color: #fff; margin-bottom: 0.5rem;">Bedankt voor je bericht!</h3>';
    $html .= '<p style="color: #ccc;">We nemen zo snel mogelijk contact met je op.</p>';
    $html .= '</div>';
    
    $html .= '</form>';
    $html .= '</div>';
    
    return $html;
}
