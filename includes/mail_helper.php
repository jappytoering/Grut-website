<?php
/**
 * Mail Helper - Transactionele E-mailengine
 *
 * Zorgt voor het genereren en versturen van nette HTML-e-mails in Grut-huisstijl.
 * Bevat een 'sandbox' modus voor lokaal testen, waarbij e-mails naar schijf worden geschreven.
 */

/**
 * Kernfunctie om e-mails te versturen of lokaal te loggen.
 */
function send_grut_email($to, $replyTo, $subject, $htmlContent) {
    global $config;
    if (!isset($config)) {
        $config = require __DIR__ . '/../config/contact.php';
    }

    $sender = $config['sender_email'] ?? 'noreply@grutdesigners.nl';

    // Headers bouwen voor HTML mail
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Grut Designers <{$sender}>\r\n";
    $headers .= "Reply-To: {$replyTo}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    if (!empty($config['mail_sandbox'])) {
        // Lokale test-modus: Schrijf weg naar storage/logs/mails/
        $timestamp = date('Y-m-d_H-i-s');
        $safeSubject = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $subject);
        $filename = "{$timestamp}_{$safeSubject}.html";
        $filepath = __DIR__ . '/../storage/logs/mails/' . $filename;
        
        $logContent = "<!-- \n";
        $logContent .= "TO: {$to}\n";
        $logContent .= "REPLY-TO: {$replyTo}\n";
        $logContent .= "SUBJECT: {$subject}\n";
        $logContent .= "HEADERS: \n{$headers}\n";
        $logContent .= "-->\n\n";
        $logContent .= $htmlContent;

        // Ensure directory exists
        if (!is_dir(__DIR__ . '/../storage/logs/mails')) {
            mkdir(__DIR__ . '/../storage/logs/mails', 0755, true);
        }

        file_put_contents($filepath, $logContent);
        return true;
    } else {
        // Live modus: Verstuur de echte mail
        return @mail($to, $subject, $htmlContent, $headers);
    }
}

/**
 * Verstuurt een uitgebreide datatabel naar de admins (Jappy & Jurrit).
 */
function send_admin_lead_notification(array $leadData) {
    global $config;
    if (!isset($config)) {
        $config = require __DIR__ . '/../config/contact.php';
    }

    $adminEmail = $config['receiver_email'];
    $leadName = $leadData['name'] ?? 'Onbekende afzender';
    $leadEmail = $leadData['email'] ?? 'geen@email.opgegeven';
    $presetId = $leadData['preset_id'] ?? 'contact';
    
    $subject = "Nieuwe aanvraag: [" . ucfirst($presetId) . "] - " . $leadName;

    $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
    $html .= '<style>';
    $html .= 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f4f5; color: #1f2937; margin: 0; padding: 20px; }';
    $html .= '.container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }';
    $html .= '.header { background-color: #2D3047; padding: 20px; color: #ffffff; }';
    $html .= '.header h1 { margin: 0; font-size: 20px; }';
    $html .= '.content { padding: 20px; }';
    $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 10px; }';
    $html .= 'th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }';
    $html .= 'th { width: 30%; color: #6b7280; font-weight: 500; font-size: 14px; }';
    $html .= 'td { color: #111827; font-weight: 400; font-size: 14px; }';
    $html .= '.footer { background-color: #f9fafb; padding: 15px; text-align: center; font-size: 12px; color: #9ca3af; }';
    $html .= '</style></head><body>';
    
    $html .= '<div class="container">';
    $html .= '<div class="header"><h1>Nieuwe Lead via Website</h1></div>';
    $html .= '<div class="content">';
    $html .= '<p>Er is een nieuw contactformulier ingevuld op de website. Je kunt direct op deze e-mail antwoorden om contact op te nemen met <strong>' . htmlspecialchars($leadName) . '</strong>.</p>';
    
    $html .= '<table><tbody>';
    
    $skipFields = ['preset_id', 'source_url', 'website_hp', 'browser_timestamp'];
    foreach ($leadData as $key => $val) {
        if (in_array($key, $skipFields) || empty($val)) continue;
        
        $label = ucfirst(str_replace(['_', '-'], ' ', $key));
        $valHtml = nl2br(htmlspecialchars($val));
        
        $html .= '<tr>';
        $html .= '<th>' . htmlspecialchars($label) . '</th>';
        $html .= '<td>' . $valHtml . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '<tr><th>Bronpagina</th><td>' . htmlspecialchars($leadData['source_url'] ?? 'Onbekend') . '</td></tr>';
    $html .= '<tr><th>Tijdstip</th><td>' . date('d-m-Y H:i:s') . '</td></tr>';
    
    $html .= '</tbody></table>';
    $html .= '</div>';
    $html .= '<div class="footer">Deze e-mail is automatisch gegenereerd door de Grut Designers website.</div>';
    $html .= '</div></body></html>';

    return send_grut_email($adminEmail, $leadEmail, $subject, $html);
}

/**
 * Verstuurt een premium bevestigingsmail naar de bezoeker.
 */
function send_customer_confirmation(array $leadData) {
    global $config;
    if (!isset($config)) {
        $config = require __DIR__ . '/../config/contact.php';
    }

    $leadEmail = $leadData['email'] ?? null;
    $leadName = $leadData['name'] ?? 'aanvrager';
    // Optioneel extraheren we de voornaam als fallback
    $firstName = explode(' ', $leadName)[0];
    
    if (!$leadEmail) return false;

    $adminEmail = $config['receiver_email'];
    $subject = "Bedankt voor je aanvraag bij Grut! 🚀";

    // Bevestiging tekst zoals gevraagd door de gebruiker
    $introText = "bedankt voor je aanvraag. Hij is in goede orde ontvangen. We nemen binnen 1 werkdag contact met je op.";

    $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
    $html .= '<style>';
    $html .= 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f4f4f5; color: #1f2937; margin: 0; padding: 20px; }';
    $html .= '.container { max-width: 600px; margin: 0 auto; background: #FFCD38; color: #2D3047; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }';
    $html .= '.header { padding: 40px 30px 10px 30px; text-align: left; }';
    $html .= '.header h1 { margin: 0; font-size: 24px; font-weight: 700; color: #2D3047; }'; 
    $html .= '.content { padding: 20px 30px 40px 30px; font-size: 16px; line-height: 1.6; color: #2D3047; }';
    $html .= '.quote-box { background-color: rgba(255, 255, 255, 0.4); border-left: 4px solid #2D3047; padding: 15px 20px; margin: 25px 0; border-radius: 0 8px 8px 0; font-size: 15px; color: #2D3047; font-style: italic; }';
    $html .= '.footer { background-color: rgba(45, 48, 71, 0.05); padding: 30px; text-align: left; font-size: 14px; color: #2D3047; border-top: 1px solid rgba(45, 48, 71, 0.1); }';
    $html .= '</style></head><body>';
    
    $html .= '<div class="container">';
    $html .= '<div class="header">';
    $html .= '<h1>Aanvraag gelukt 🤘</h1>';
    $html .= '</div>';
    
    $html .= '<div class="content">';
    $html .= '<p>Beste ' . htmlspecialchars($firstName) . ',<br><br>' . $introText . '</p>';
    
    // Toon de ingestuurde vraag/casus indien aanwezig
    $case = $leadData['message'] ?? ($leadData['case_description'] ?? null);
    if ($case) {
        $html .= '<p>Voor de volledigheid, dit is wat je ons hebt gestuurd:</p>';
        $html .= '<div class="quote-box">"' . nl2br(htmlspecialchars($case)) . '"</div>';
    }
    
    $html .= '<p>Groet,<br><br><strong>Jappy & Jurrit van Grut</strong></p>';
    $html .= '</div>';
    
    $html .= '<div class="footer">';
    $html .= '<p><strong>Grut Designers</strong><br>Digital Product Design & UX Strategie<br><a href="https://grutdesigners.nl" style="color: #6b7280;">grutdesigners.nl</a></p>';
    $html .= '</div>';
    
    $html .= '</div></body></html>';

    // Reply-To naar Grut zodat als ze reageren, het direct naar jullie gaat
    return send_grut_email($leadEmail, $adminEmail, $subject, $html);
}
