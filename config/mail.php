<?php
declare(strict_types=1);

return [
	'driver' => getenv('MAIL_DRIVER') ?: 'smtp',
	'host' => getenv('MAIL_HOST') ?: 'smtp.example.com',
	'port' => (int)(getenv('MAIL_PORT') ?: 587),
	'username' => getenv('MAIL_USERNAME') ?: '',
	'password' => getenv('MAIL_PASSWORD') ?: '',
	'encryption' => getenv('MAIL_ENCRYPTION') ?: 'tls',
	'from_address' => getenv('MAIL_FROM_ADDRESS') ?: 'noreply@example.com',
	'from_name' => getenv('MAIL_FROM_NAME') ?: "Joanne's Boutique",
];


