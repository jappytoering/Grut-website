#!/bin/bash
# Lokaal startscript voor Grut Designers (PHP & SQLite)

echo "🚀 Start lokale ontwikkelomgeving..."
echo "De website is straks bereikbaar via: http://localhost:8000"
echo "Druk op CTRL+C om de server te stoppen."
echo ""

# Ga naar de root directory van de website
cd "$(dirname "$0")"

# Controleer of php geïnstalleerd is
if ! command -v php &> /dev/null
then
    echo "❌ FOUT: PHP is niet geïnstalleerd of staat niet in je PATH."
    echo "Tip: Gebruik MAMP, Herd of installeer PHP via Homebrew (brew install php)."
    exit 1
fi

# Initialiseer de database als dit nodig is
if [ -f "init-db.php" ]; then
    echo "🛠️ Controleren/initialiseren databases..."
    php init-db.php
fi

# Start de server
php -S localhost:8000 -t .
