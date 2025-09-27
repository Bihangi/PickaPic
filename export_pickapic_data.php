<?php

$host = 'localhost';
$username = 'root'; 
$password = ''; 
$database = 'pickapic';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to pickapic database!\n";
    
    // Define tables you want to export 
    $tablesToExport = [
        'cache',
        'cache_locks',
        'failed_jobs',
        'jobs',
        'job_batches',
        'migrations',
        'notifications',
        'password_reset_tokens',
        'personal_access_tokens',
        'photographers',
        'portfolios',
        'sessions',
        'users',
        'pending_registrations', 
        'bookings',
        'packages',
        'availabilities',
        'reviews',
        'conversations',
        'messages',
        'premium_requests'
    ];
    
    foreach ($tablesToExport as $table) {
        if (tableExists($pdo, $table)) {
            exportTable($pdo, $table);
        } else {
            echo "Table '$table' not found, skipping...\n";
        }
    }
    
    // Generate DatabaseSeeder.php
    generateDatabaseSeeder($tablesToExport);
    
    echo "\nData export completed!\n";
    echo "Next steps:\n";
    echo "1. Copy the generated seeder files to your Laravel project's database/seeders/ directory\n";
    echo "2. Run: php artisan migrate:fresh --seed\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

function tableExists($pdo, $tableName)
{
    $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmt->execute([$tableName]);
    return $stmt->rowCount() > 0;
}

function exportTable($pdo, $tableName)
{
    // Get table data
    $stmt = $pdo->query("SELECT * FROM `$tableName`");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($data)) {
        echo "Table '$tableName' is empty, skipping...\n";
        return;
    }
    
    $className = 'Backup' . pascalCase($tableName) . 'Seeder';
    
    $seederContent = generateSeederContent($className, $tableName, $data);
    
    // Create directory if it doesn't exist
    if (!is_dir('database_seeders')) {
        mkdir('database_seeders', 0755, true);
    }
    
    file_put_contents("database_seeders/$className.php", $seederContent);
    echo "Exported $tableName (" . count($data) . " records) -> $className.php\n";
}

function generateSeederContent($className, $tableName, $data)
{
    $content = "<?php\n\n";
    $content .= "namespace Database\\Seeders;\n\n";
    $content .= "use Illuminate\\Database\\Seeder;\n";
    $content .= "use Illuminate\\Support\\Facades\\DB;\n";
    $content .= "use Carbon\\Carbon;\n\n";
    $content .= "class $className extends Seeder\n";
    $content .= "{\n";
    $content .= "    public function run()\n";
    $content .= "    {\n";
    $content .= "        DB::table('$tableName')->insert([\n";
    
    foreach ($data as $row) {
        $content .= "            [\n";
        foreach ($row as $column => $value) {
            $formattedValue = formatValue($value);
            $content .= "                '$column' => $formattedValue,\n";
        }
        $content .= "            ],\n";
    }
    
    $content .= "        ]);\n";
    $content .= "    }\n";
    $content .= "}\n";
    
    return $content;
}

function formatValue($value)
{
    if (is_null($value)) {
        return 'null';
    }
    
    if (is_numeric($value)) {
        return $value;
    }
    
    if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/', $value)) {
        return "'$value'";
    }
    
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    
    // Escape single quotes and wrap in quotes
    $escaped = str_replace("'", "\\'", $value);
    return "'$escaped'";
}

function pascalCase($string)
{
    return str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $string)));
}

function generateDatabaseSeeder($tables)
{
    $content = "<?php\n\n";
    $content .= "namespace Database\\Seeders;\n\n";
    $content .= "use Illuminate\\Database\\Seeder;\n\n";
    $content .= "class DatabaseSeeder extends Seeder\n";
    $content .= "{\n";
    $content .= "    public function run()\n";
    $content .= "    {\n";
    $content .= "        \$this->call([\n";
    
    foreach ($tables as $table) {
        $className = 'Backup' . pascalCase($table) . 'Seeder';
        $content .= "            $className::class,\n";
    }
    
    $content .= "        ]);\n";
    $content .= "    }\n";
    $content .= "}\n";
    
    file_put_contents("database_seeders/DatabaseSeeder.php", $content);
    echo "Generated DatabaseSeeder.php\n";
}
?>