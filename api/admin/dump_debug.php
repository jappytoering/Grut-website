<?php
$file = __DIR__ . '/../../storage/cms_debug.txt';
if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    echo "No debug file.";
}
