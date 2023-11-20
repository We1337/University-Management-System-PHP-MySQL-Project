<?php
/**
 * Defines the routes for a web application.
 *
 * @return array The array that defines the routes for the web application.
 */
return [
    'routes' => [
        '/' => 'HomeController@index',
        '/about' => 'PageController@about',
        '/contact' => 'ContactController@index',
    ],
];