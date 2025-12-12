<?php

<<<<<<< HEAD
return [
    'host' => 'slubazaar.local',
    'user' => 'root', # your username boi
    'pass' => '', # your password boi
    'name' => 'slubazaar',# name the file this way boi
    'port' => '3306'
]; # AND DO NOT COMMIT ANY CHANGES HERE, WE HAVE DIFFERENT ACCOUNTS
=======
function getEnvVar($key, $default = null)
{
    $val = getenv($key);
    return ($val !== false) ? $val : $default;
}

// Render config
if (getEnvVar('DB_HOST')) {
    return [
        'host' => getEnvVar('DB_HOST'),
        'user' => getEnvVar('DB_USER'),
        'pass' => getEnvVar('DB_PASS'),
        'name' => getEnvVar('DB_NAME'),
        'port' => getEnvVar('DB_PORT', 3306)
    ];
} else {
    // LOCAL CONFIG (XAMPP defaults)
    return [ #DEFAULTS
        'host' => 'localhost', #PAG NI-UPDATE MO TO, WAG MO NANG IPUSH
        'user' => 'root', #PAG NI-UPDATE MO TO, WAG MO NANG IPUSH
        'pass' => '',    #PAG NI-UPDATE MO TO, WAG MO NANG IPUSH
        'name' => 'slubazaar',
        'port' => 3306
    ];
}
>>>>>>> 0c7835089475e4b998ff4eccd0b3dd5793fefa12
