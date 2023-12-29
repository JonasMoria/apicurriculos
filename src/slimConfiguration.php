<?php

namespace src;

function slimConfiguration(): \Slim\Container {
    $config = [
        'settings' => [
            'displayErrorDetails' => getenv('DISPLAY_ERRORS_DETAILS'),
        ]
    ];

    return new \Slim\Container($config);
}