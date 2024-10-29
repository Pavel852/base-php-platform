<?php
/**
 * installer/index.php - Installer Script
 * Version: 1.0
 * Release Date: 10/2024
 * Author: PB
 * Email: pavel.bartos.pb@gmail.com
 */

// Kontrola, zda byl skript zahrnut prostřednictvím index.php
if (!defined('BASE_PHP_PLATFORM')) {
    http_response_code(403);
    echo "403 Forbidden - Direct access to installer is not allowed.";
    exit;
}

// Instalace logika zde
echo "<h1>Installer</h1>";
echo "<p>Welcome to the Base PHP Platform Installer.</p>";

// Zde implementujte instalační logiku, například:
echo "<p>Instalace probíhá...</p>";
// ... další instalační kroky ...

?>
