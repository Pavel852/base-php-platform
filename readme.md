# Base PHP Platform

## Popis projektu

**Base PHP Platform** je jednoduchý směrovací systém napsaný v PHP, který umožňuje spouštět různé aplikace umístěné ve složce `./app/`. Pokud není specifikována žádná aplikace, systém automaticky spustí instalační skript ve složce `./installer/`. Tento systém je navržen tak, aby poskytoval bezpečné a efektivní směrování mezi různými částmi aplikace a zároveň udržoval přehlednou strukturu složek.

## Struktura složek

Projekt má následující strukturu složek:

```
base-php-platform/
├── app/
│   ├── test/
│   │   └── index.php
│   └── AnotherApp/
│       └── index.php
├── cron/
│   └── ... (cron skripty, včetně soupštěcích PHP)
├── data/
│   └── ... (data soubory, soubory, sqlite.db, ...)
├── inc/
│   └── ... (hotové podprogramy pro správu, phpliteadmin, ...)
├── installer/
│   └── index.php (instalační PHP kód)
├── lang/
│   └── cz.system.ini (jazykové soubory)
├── lib/
│   └── ... (include soubory, vendor, funkce, knihovny, ...)
├── tmp/
│   └── ... (dočasné soubory)
├── composer.json
└── index.php
```

### Popis složek

- **app/**: Obsahuje jednotlivé aplikace, které mohou být spuštěny prostřednictvím směrovacího systému.
- **cron/**: Ukládá cron skripty pro plánované úlohy.
- **data/**: Obsahuje datové soubory používané aplikací.
- **inc/**: Ukládá podprogramy, které jsou využívány napříč projektem.
- **installer/**: Obsahuje instalační skript, který se spustí, pokud není specifikována žádná aplikace.
- **lang/**: Obsahuje jazykové soubory ve formátu [jazyk].[název].ini (např. cz.system.ini).
- **lib/**: Ukládá knihovny a další závislosti projektu.
- **tmp/**: Obsahuje dočasné soubory generované aplikací.
- **composer.json**: Konfigurační soubor pro Composer, který spravuje závislosti projektu.
- **index.php**: Hlavní vstupní bod pro směrovací systém.


## Použití

### Inicializace směrovacího systému

Hlavní skript `index.php` je zodpovědný za směrování požadavků na správnou aplikaci nebo instalační skript. Níže je přehled funkcionalit tohoto skriptu:

1. **Definice základních adresářů**:
    ```php
    define('DIR_APP', __DIR__ . '/app/');
    define('DIR_CRON', __DIR__ . '/cron/');
    define('DIR_DATA', __DIR__ . '/data/');
    define('DIR_INC', __DIR__ . '/inc/');
    define('DIR_INSTALLER', __DIR__ . '/installer/');
    define('DIR_LIB', __DIR__ . '/lib/');
    define('DIR_TMP', __DIR__ . '/tmp/');
    ```

2. **Definice parametru `configApp`**:
    ```php
    $configApp = ''; // Výchozí je prázdný, což spustí instalační skript
    // Pokud chcete spustit konkrétní aplikaci, odkomentujte a nastavte název aplikace:
    // $configApp = 'ExampleApp'; // Příklad nastavení konkrétní aplikace
    ```

3. **Přepsání `configApp` pomocí GET parametru `loadapp`**:
    ```php
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
    ```

4. **Funkce pro bezpečné načtení skriptu**:
    ```php
    function loadScript($path) {
        if (file_exists($path)) {
            include $path;
        } else {
            http_response_code(404);
            echo "404 Not Found - The requested application does not exist.";
            exit;
        }
    }
    ```

5. **Spusťte instalační skript**:

- Otevřete index.php ve vašem prohlížeči bez parametru loadapp.
- Nebo použijte následující URL:
    ```php
http://yourdomain.com/index.php
    ```

## Instalace
**Klonejte repozitář**:

    ```bash
git clone https://github.com/Pavel852/base-php-platform.git ./
    ```

**Přejděte do složky projektu**:

    ```bash
cd base-php-platform
    ```

**Nainstalujte závislosti pomocí **:

    ```bash
composer install
    ```

**Nastavte práva pro dočasné složky**:

    ```bash
chmod -R 0777 tmp/
    ```


## Použití
- Spuštění aplikace:
  - Nastavte $configApp v index.php nebo použijte GET parametr loadapp pro spuštění konkrétní aplikace.
- Správa cron úloh:
  - Uložte vaše cron skripty do složky ./cron/.
- Ukládání dat:
  - Používejte složku ./data/ pro ukládání datových souborů.
- Includování souborů:
  - Vkládejte sdílené soubory do složky ./inc/.
- Správa knihoven:
  - Ukládejte knihovny do složky ./lib/.
  - ./lang/: Obsahuje jazykové soubory ve formátu [jazyk].[název].ini (např. cz.system.ini).
- Dočasné soubory:
  - Všechny dočasné soubory jsou ukládány do složky ./tmp/.

## Jak přidat nový jazyk
1. Vytvořte nové .ini soubory v adresáři ./lang/ s kódem nového jazyka.

- Například pro němčinu:
  - de.system.ini
  - de.messages.ini

2.Přidejte lokalizované texty do těchto souborů.

Například v de.system.ini:
invalid_app_param = "400 Ungültige Anfrage - Ungültiger Anwendungsparameter."

3. Přepněte jazyk pomocí parametru lang v URL:

http://yourdomain.com/index.php?lang=de

## Autorská práva
- Autor: PB
- Email: pavel.bartos.pb@gmail.com
- Licence: MIT

## Podpora
Pokud máte jakékoliv otázky nebo potřebujete pomoc, kontaktujte autora na emailu: pavel.bartos.pb@gmail.com.

## Verze
- Version: 1.0
- Release Date: 10/2024

## Další informace
- Homepage: https://github.com/Pavel852/base-php-platform
- Issues: https://github.com/Pavel852/base-php-platform/issues
- Dokumentace: README.md
- Licence: MIT

