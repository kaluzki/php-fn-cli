<?php
/**
 * Copyright (C) php-fn. See LICENSE file for license details.
 */

foreach ([
    '\fn\cli'      => 'functions-cli.php',
] as $fnc => $file) {
    if (!function_exists($fnc)) {
        require_once __DIR__ . "/fn/$file" ;
    }
}
