<?php
// config/bootstrap.php

// Autoload dependencies using Composer's autoload (must be first)
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables from the .env file
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Define constants for API keys and other configurations
define('PAYPAL_CLIENT_ID', $_ENV['PAYPAL_CLIENT_ID'] ?? $_SERVER['PAYPAL_CLIENT_ID'] ?? getenv('PAYPAL_CLIENT_ID') ?: '');
define('PAYPAL_CLIENT_SECRET', $_ENV['PAYPAL_CLIENT_SECRET'] ?? $_SERVER['PAYPAL_CLIENT_SECRET'] ?? getenv('PAYPAL_CLIENT_SECRET') ?: ($_ENV['PAYPAL_SECRET'] ?? $_SERVER['PAYPAL_SECRET'] ?? getenv('PAYPAL_SECRET') ?: ''));

// Additional configuration settings can be added here
// For example, database connection settings, logging configurations, etc.

// Initialize application settings or services here if needed
?>