<?php

declare(strict_types=1);

// 1. Load the Container Class
require_once __DIR__ . '/Container.php';

// 2. Initialize the Container
$container = new Container();

// 3. Return it so index.php can use it
return $container;