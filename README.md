# Grut Designers - Website & Lead Engine

Welkom bij de codebase van de Grut Designers website. Deze setup gebruikt statische PHP/HTML templates voor maximale performance en een modulaire SQLite backend voor de verwerking van formulieren.

## Lokaal Ontwikkelen

Je kunt de website lokaal draaien zonder ingewikkelde server-setup via de ingebouwde PHP webserver.

1. Open je terminal in deze map.
2. Start de server met:
   ```bash
   php -S localhost:8000
   ```
3. Ga in je browser naar `http://localhost:8000`

### Zero-config Database
Wanneer het eerste formulier wordt ingediend (of de eerste database-connectie wordt gemaakt), zal de map `storage/` automatisch de database `leads.sqlite` aanmaken als deze nog niet bestaat. Je hoeft lokaal dus geen tabellen aan te leggen.

## Formulieren Toevoegen
Formulieren zijn geconfigureerd in `includes/form_helper.php` via de `$FORM_PRESETS` array.

1. Voeg een preset toe aan de array.
2. Roep in je template het formulier aan:
   ```php
   <?php
   require_once __DIR__ . '/includes/form_helper.php';
   echo render_grut_form('prototype-sprint');
   ?>
   ```

## Git Workflow
- `storage/*.sqlite` en mail logs worden genegeerd door Git. Zo worden lokale testinzendingen nooit per ongeluk gedeployd.
- Werk in een eigen branch voor nieuwe features: `git checkout -b feature/nieuwe-pagina`
- Test lokaal en push naar de staging omgeving voor review.
