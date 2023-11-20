<?php

/**
 * Returns a configuration array with three key-value pairs.
 *
 * @return array The configuration array with the following key-value pairs:
 *               - 'base_url' with the value 'https://localhost/'
 *               - 'timezone' with the value 'UTC'
 *               - 'debug' with the value true
 *
 * @example
 * ```php
 * $config = include 'config.php';
 * echo $config['base_url']; // Output: 'https://localhost/'
 * echo $config['timezone']; // Output: 'UTC'
 * echo $config['debug']; // Output: true
 * ```
 */
return [
    'base_url' => 'https://localhost/',
    'timezone' => 'UTC',
    'debug' => true,
];