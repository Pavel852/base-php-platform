<?php
/**
 * index.php - Simple Routing System
 * Version: 1.0
 * Release Date: 10/2024
 * Author: PB
 * Email: pavel.bartos.pb@gmail.com
 */

// Verze aplikace
$version = "1.0";
$releaseDate = "10/2024";
$author = "PB";
$authorEmail = "pavel.bartos.pb@gmail.com";

// Nastavení HTTP hlaviček pro směrování
header("Content-Type: text/html; charset=UTF-8");

// Definice konstanty pro kontrolu přístupu k instalačnímu skriptu
define('BASE_PHP_PLATFORM', true);

// Definice základních adresářů jako konstanty
define('DIR_APP', __DIR__ . '/app/');
define('DIR_CRON', __DIR__ . '/cron/');
define('DIR_DATA', __DIR__ . '/data/');
define('DIR_INC', __DIR__ . '/inc/');
define('DIR_INSTALLER', __DIR__ . '/installer/');
define('DIR_LIB', __DIR__ . '/lib/');
define('DIR_TMP', __DIR__ . '/tmp/');

// Definice parametru 'config_app' přímo ve skriptu
$configApp = ''; // Výchozí je prázdný, což spustí instalační skript
// Pokud chcete spustit konkrétní aplikaci, odkomentujte a nastavte název aplikace:
// $configApp = 'ExampleApp'; // Příklad nastavení konkrétní aplikace

// Přepsání $configApp pomocí GET parametru 'loadapp', pokud je zadán
if (isset($_GET['loadapp'])) {
    $loadAppParam = trim($_GET['loadapp']);
    if ($loadAppParam !== '') {
        // Ověření, že 'loadapp' obsahuje pouze povolené znaky (písmena, čísla, podtržítka)
        if (preg_match('/^[a-zA-Z0-9_]+$/', $loadAppParam)) {
            $configApp = $loadAppParam;
        } else {
            http_response_code(400);
            echo "400 Bad Request - Invalid application parameter.";
            exit;
        }
    }
}

/**
 * Funkce pro bezpečné načtení souboru
 *
 * @param string $path Cesta k souboru
 * @return void
 */
function loadScript($path) {
    if (file_exists($path)) {
        include $path;
    } else {
        http_response_code(404);
        echo "404 Not Found - The requested application does not exist.";
        exit;
    }
}

// Směrování na příslušnou aplikaci nebo instalační skript
if (!empty($configApp)) {
    // Cesta k aplikaci na základě parametru 'config_app'
    $appPath = DIR_APP . basename($configApp) . "/index.php";

    // Získání absolutní cesty
    $realAppPath = realpath($appPath);
    $realAppDir = realpath(DIR_APP);

    // Kontrola, zda je skutečná cesta uvnitř složky ./app/
    if ($realAppPath === false || strpos($realAppPath, $realAppDir) !== 0) {
        http_response_code(400);
        echo "400 Bad Request - Invalid application path.";
        exit;
    }

    // Načtení aplikace
    loadScript($realAppPath);
} else {
    // Pokud parametr 'config_app' není zadán, načíst instalační skript
    $installerPath = DIR_INSTALLER . "index.php";

    // Získání absolutní cesty
    $realInstallerPath = realpath($installerPath);
    $realInstallerDir = realpath(DIR_INSTALLER);

    // Kontrola, zda je skutečná cesta uvnitř složky ./installer/
    if ($realInstallerPath === false || strpos($realInstallerPath, $realInstallerDir) !== 0) {
        http_response_code(500);
        echo "500 Internal Server Error - Installer script not found.";
        exit;
    }

    // Načtení instalačního skriptu
    loadScript($realInstallerPath);
}

?>

