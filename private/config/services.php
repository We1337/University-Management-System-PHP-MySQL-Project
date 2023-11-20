<?php

/**
 * Returns the configuration array for sending emails using SMTP.
 *
 * Example Usage:
 * ```php
 * $config = [
 *     'mail' => [
 *         'driver' => 'smtp',
 *         'host' => 'smtp.example.com',
 *         'username' => 'email@example.com',
 *         'password' => 'email_password',
 *     ],
 * ];
 * ```
 *
 * @return array The configuration array for sending emails using SMTP.
 */
return [
    'mail' => [
        'driver' => 'smtp',
        'host' => 'smtp.example.com',
        'username' => 'email@example.com',
        'password' => 'email_password',
    ],
];