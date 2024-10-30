<?php
/**
 * function.php - Funkce pro Simple Routing System
 * Version: 1.2
 * Release Date: 10/2024
 * Author: PB
 * Email: pavel.bartos.pb@gmail.com
 */

// Zabránění přímému přístupu k souboru
if (!defined('BASE_PHP_PLATFORM')) {
    http_response_code(403);
    die($language['errors']['access_denied']);
}

/**
 * Funkce pro zobrazení verze routeru.
 * Vypíše verzi aplikace, datum vydání, autora a email autora.
 */
function router_version() {
    global $version, $releaseDate, $author, $authorEmail, $language;

    echo "<div class='router-version'>";
    echo "<strong>" . htmlspecialchars($language['router']['router_version_label']) . "</strong> " . htmlspecialchars($version) . "<br>";
    echo "<strong>" . htmlspecialchars($language['router']['router_release_date_label']) . "</strong> " . htmlspecialchars($releaseDate) . "<br>";
    echo "<strong>" . htmlspecialchars($language['router']['router_author_label']) . "</strong> " . htmlspecialchars($author) . "<br>";
    echo "<strong>" . htmlspecialchars($language['router']['router_author_email_label']) . "</strong> " . htmlspecialchars($authorEmail) . "<br>";
    echo "</div>";
}

/**
 * Funkce pro zobrazení debug informací.
 *
 * Vypisuje:
 * - Aktuální jazyk.
 * - Načtené jazykové soubory z ./lang/.
 * - Obsah proměnných $_SESSION, $_POST a $_GET.
 * - Načtené knihovny z vendor pomocí autoload.php.
 */
function router_debug() {
    global $lang, $language;

    echo "<hr>";
    echo "<div class='router-debug'>";

    // Aktuální jazyk
    echo "<h3>" . htmlspecialchars($language['debug']['debug_current_language']) . "</h3>";
    echo "<pre>" . htmlspecialchars($lang) . "</pre>";

    // Načtené .ini soubory z ./lang/
    echo "<h3>" . htmlspecialchars($language['debug']['debug_loaded_language_files']) . "</h3>";
    $langDir = DIR_LANG;
    $langFiles = glob($langDir . $lang . '.*.ini');

    if (!empty($langFiles)) {
        echo "<ul>";
        foreach ($langFiles as $file) {
            echo "<li>" . htmlspecialchars(basename($file)) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>" . htmlspecialchars($language['debug']['debug_no_language_files']) . "</p>";
    }

    // $_SESSION proměnné
    echo "<h3>" . htmlspecialchars($language['debug']['debug_session_variables']) . "</h3>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";

    // $_POST proměnné
    echo "<h3>" . htmlspecialchars($language['debug']['debug_post_variables']) . "</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // $_GET proměnné
    echo "<h3>" . htmlspecialchars($language['debug']['debug_get_variables']) . "</h3>";
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";

    // Načtené knihovny z vendor pomocí autoload.php
    echo "<h3>" . htmlspecialchars($language['debug']['debug_loaded_vendor_libraries']) . "</h3>";
    $declaredClasses = get_declared_classes();
    $vendorDir = realpath(DIR_LIB . 'vendor') . DIRECTORY_SEPARATOR;

    $vendorClasses = array_filter($declaredClasses, function($class) use ($vendorDir) {
        try {
            $reflector = new ReflectionClass($class);
            $filename = $reflector->getFileName();
            return $filename && strpos($filename, $vendorDir) === 0;
        } catch (ReflectionException $e) {
            return false;
        }
    });

    if (!empty($vendorClasses)) {
        echo "<ul>";
        foreach ($vendorClasses as $class) {
            echo "<li>" . htmlspecialchars($class) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>" . htmlspecialchars($language['debug']['debug_no_vendor_libraries']) . "</p>";
    }

    echo "</div>";
}
?>

