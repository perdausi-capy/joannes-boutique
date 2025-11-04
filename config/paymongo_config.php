<?php
// config/paymongo_config.php

function getPayMongoKeys() {
    return [
        'secret_key' => $_ENV['PAYMONGO_SECRET_KEY'] ?? '',
        'public_key' => $_ENV['PAYMONGO_PUBLIC_KEY'] ?? '',
        // Add here if you set a webhook secret in .env e.g. 'webhook_secret' => ...
    ];
}
