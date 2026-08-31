<?php
/**
 * Component: CTA Formulier
 * Expects $content variable from engine.php
 * 
 * $content structuur:
 * - preset_id (string, verplicht) - e.g., 'prototype-sprint'
 * - config (array, optioneel) - custom overrides voor het formulier
 */
require_once __DIR__ . '/../includes/form_helper.php';

$preset_id = $content['preset_id'] ?? 'quick-connect';
$config = $content['config'] ?? [];

echo render_cta_block($preset_id, $config);
?>
