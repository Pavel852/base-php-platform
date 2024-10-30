<?php
/**
 * lib/Database.php
 *
 * Správce připojení k databázím.
 */

// Zabránění přímému přístupu
if (!defined('BASE_PHP_PLATFORM')) {
    http_response_code(403);
    die(isset($language['errors']['access_denied']) ? $language['errors']['access_denied'] : 'Access Denied');
}

class Database
{
    private static $instances = [];
    private $pdo;

    private function __construct($connectionName, $config)
    {
        if (!isset($config['connections'][$connectionName])) {
            throw new Exception("Databázové připojení '{$connectionName}' není definováno.");
        }

        $connection = $config['connections'][$connectionName];

        switch ($connection['driver']) {
            case 'mysql':
                $dsn = "mysql:host={$connection['host']};dbname={$connection['database']};charset={$connection['charset']}";
                $this->pdo = new PDO($dsn, $connection['username'], $connection['password'], $connection['options']);
                break;

            case 'sqlite':
                $dsn = "sqlite:{$connection['database']}";
                $this->pdo = new PDO($dsn, null, null, $connection['options']);
                break;

            default:
                throw new Exception("Nepodporovaný databázový driver: {$connection['driver']}");
        }
    }

    // Získání instance pro konkrétní připojení
    public static function getInstance($connectionName = null)
    {
        $config = require __DIR__ . '/dbconfig.php';

        if ($connectionName === null) {
            $connectionName = $config['default'];
        }

        if (!isset(self::$instances[$connectionName])) {
            self::$instances[$connectionName] = new self($connectionName, $config);
        }

        return self::$instances[$connectionName];
    }

    // Získání PDO instance
    public function getConnection()
    {
        return $this->pdo;
    }
}
