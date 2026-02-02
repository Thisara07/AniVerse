<?php
/**
 * AWS Elastic Beanstalk / Laravel Diagnostic Script
 */

header('Content-Type: text/plain');

echo "=== System Information ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "SAPI: " . php_sapi_name() . "\n";
echo "User: " . get_current_user() . " (UID: " . posix_getuid() . ")\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";

echo "\n=== Extensions Check ===\n";
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'bcmath', 'xml', 'curl', 'gd', 'exif'];
foreach ($required_extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "LOADED" : "MISSING") . "\n";
}

echo "\n=== Directory Permissions ===\n";
$dirs = [
    '../storage',
    '../storage/logs',
    '../storage/framework/sessions',
    '../storage/framework/views',
    '../storage/framework/cache',
    '../bootstrap/cache'
];
foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        echo "$dir: NOT FOUND\n";
    } else {
        echo "$dir: " . (is_writable($dir) ? "WRITABLE" : "NOT WRITABLE") . " (" . substr(sprintf('%o', fileperms($dir)), -4) . ")\n";
    }
}

echo "\n=== Environment Variables ===\n";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'NOT SET') . "\n";
echo "APP_DEBUG: " . (getenv('APP_DEBUG') ?: 'NOT SET') . "\n";
echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";
echo "APP_KEY set? " . (getenv('APP_KEY') ? "YES (Length: " . strlen(getenv('APP_KEY')) . ")" : "NO") . "\n";

echo "\n=== Autoload Test ===\n";
if (file_exists('../vendor/autoload.php')) {
    echo "Autoload file found.\n";
    try {
        require '../vendor/autoload.php';
        echo "Autoload loaded successfully.\n";
    } catch (\Throwable $e) {
        echo "ERROR LOADING AUTOLOAD: " . $e->getMessage() . "\n";
    }
} else {
    echo "Autoload file NOT FOUND at ../vendor/autoload.php\n";
}

echo "\n=== Database Connection Test ===\n";
$host = getenv('DB_HOST');
$db   = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$port = getenv('DB_PORT') ?: '3306';

if (!$host || !$db) {
    echo "DB Configuration missing from environment.\n";
} else {
    echo "Attempting connection to $host:$port...\n";
    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT            => 5, // 5 second timeout
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);
        echo "SUCCESS: Connected to database!\n";
    } catch (\PDOException $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
        echo "HINT: If this times out, check your RDS Security Group Inbound Rules.\n";
    }
}

echo "\n=== End of Diagnostics ===\n";
