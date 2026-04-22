<?php
$files = glob(__DIR__ . '/../storage/app/public/prescriptions/*.pdf');
foreach ($files as $f) {
    if (filesize($f) < 100) {
        unlink($f);
        echo "deleted $f\n";
    }
}
