<?php
/**
 * index.php - Simple Routing System
 * Version: 1.2
 * Release Date: 10/2024
 * Author: PB
 * Email: pavel.bartos.pb@gmail.com
 */

// Start session
session_start();

// Verze aplikace
$version = "1.2";
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
define('DIR_LANG', __DIR__ . '/lang/');
define('DIR_TMP', __DIR__ . '/tmp/');

// Nastavení výchozího jazyka
$lang = 'cz'; // Zde můžete nastavit výchozí jazyk

// Definice parametru 'configApp' přímo ve skriptu
$configApp = ''; // Výchozí je prázdný, což spustí instalační skript
// Pokud chcete spustit konkrétní aplikaci, odkomentujte a nastavte název aplikace:
// $configApp = 'ExampleApp'; // Příklad nastavení konkrétní aplikace

// Načtení autoloaderu Composeru, pokud existuje
$composerAutoload = DIR_LIB . 'vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
}


// Kontrola nastavení jazyka v GET parametru
if (isset($_GET['lang'])) {
    $langParam = trim($_GET['lang']);
    // Ověření, že 'lang' obsahuje pouze povolené znaky (dvoupísmenné kódy)
    if (preg_match('/^[a-z]{2}$/', $langParam)) {
        $lang = $langParam;
        $_SESSION['lang'] = $lang;
    }
}

// Kontrola, zda je jazyk uložen v session
if (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
}

// Funkce pro načtení všech .ini souborů pro daný jazyk a vytvoření proměnných
function loadLanguageFiles($lang) {
    $langDir = DIR_LANG;
    $langFiles = glob($langDir . $lang . '.*.ini');

    foreach ($langFiles as $file) {
        $data = parse_ini_file($file);
        if ($data !== false) {
            foreach ($data as $key => $value) {
                // Ošetření názvu proměnné
                $varName = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
                global $$varName;
                $$varName = $value;
            }
        }
    }
}

// Načtení jazykových souborů
loadLanguageFiles($lang);

// Přepsání $configApp pomocí GET parametru 'loadapp', pokud je zadán
if (isset($_GET['loadapp'])) {
    $loadAppParam = trim($_GET['loadapp']);
    if ($loadAppParam !== '') {
        // Ověření, že 'loadapp' obsahuje pouze povolené znaky (písmena, čísla, podtržítka)
        if (preg_match('/^[a-zA-Z0-9_]+$/', $loadAppParam)) {
            $configApp = $loadAppParam;
        } else {
            http_response_code(400);
            echo isset($invalid_app_param) ? $invalid_app_param : "400 Bad Request - Neplatný parametr aplikace.";
            exit;
        }
    }
}

// Směrování na příslušnou aplikaci nebo instalační skript
if (!empty($configApp)) {
    // Cesta k aplikaci na základě parametru 'configApp'
    $appPath = DIR_APP . basename($configApp) . "/index.php";

    // Získání absolutní cesty
    $realAppPath = realpath($appPath);
    $realAppDir = realpath(DIR_APP);

    // Kontrola, zda je skutečná cesta uvnitř složky ./app/
    if ($realAppPath === false || strpos($realAppPath, $realAppDir) !== 0) {
        http_response_code(400);
        echo isset($invalid_app_path) ? $invalid_app_path : "400 Bad Request - Neplatná cesta k aplikaci.";
        exit;
    }

    // Načtení aplikace
    if (file_exists($realAppPath)) {
        include $realAppPath;
    } else {
        http_response_code(404);
        echo isset($app_not_found) ? $app_not_found : "404 Not Found - Požadovaná aplikace neexistuje.";
        exit;
    }
} else {
    // Pokud parametr 'configApp' není zadán, načíst instalační skript
    $installerPath = DIR_INSTALLER . "index.php";

    // Získání absolutní cesty
    $realInstallerPath = realpath($installerPath);
    $realInstallerDir = realpath(DIR_INSTALLER);

    // Kontrola, zda je skutečná cesta uvnitř složky ./installer/
    if ($realInstallerPath === false || strpos($realInstallerPath, $realInstallerDir) !== 0) {
        http_response_code(500);
        echo isset($installer_not_found) ? $installer_not_found : "500 Internal Server Error - Instalační skript nenalezen.";
        exit;
    }

    // Načtení instalačního skriptu
    if (file_exists($realInstallerPath)) {
        include $realInstallerPath;
    } else {
        http_response_code(500);
        echo isset($installer_not_found) ? $installer_not_found : "500 Internal Server Error - Instalační skript nenalezen.";
        exit;
    }
}
?>

