<?php
return [
    // Výchozí připojení (pokud není specifikováno jinak)
    'default' => 'mysql1',

    // Definice jednotlivých připojení
    'connections' => [
        'mysql1' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'databaze1',
            'username'  => 'uzivatel1',
            'password'  => 'heslo1',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'options'   => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        ],
        'mysql2' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'databaze2',
            'username'  => 'uzivatel2',
            'password'  => 'heslo2',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'options'   => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        ],
        'sqlite1' => [
            'driver'   => 'sqlite',
            'database' => __DIR__ . '/../data/databaze1.sqlite',
            'options'  => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ],
        ],
    ],
];

?>