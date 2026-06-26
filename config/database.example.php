<?php
return [
    'host'    => getenv('DB_HOST') ?: 'localhost',
    'dbname'  => getenv('DB_NAME') ?: 'internal_asset_exchange_db',
    'user'    => getenv('DB_USER') ?: 'root',
    'pass'    => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4'
];