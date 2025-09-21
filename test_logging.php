<?php

// Test the LogService functionality

require_once 'vendor/autoload.php';

use App\Services\LogService;

// Test various logging methods
LogService::info('test', 'Testing log service functionality');
LogService::auth('login', 'test@example.com');
LogService::userAction('created', 'Test User', ['test' => true]);

echo "Log entries created successfully!\n";
