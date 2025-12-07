<?php
return [
    'host' => getenv('DB_HOST'),
    'user' => getenv('DB_USER'),
    'pass' => getenv('DB_PASS'),
    'name' => getenv('DB_NAME'),
    'port' => getenv('DB_PORT') ?: 3306
];
