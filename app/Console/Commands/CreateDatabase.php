<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    protected $signature = 'db:create';
    protected $description = 'Create the database if it does not exist';

    public function handle()
    {
        $database = config('database.connections.mysql.database');
        
        try {
            $pdo = new \PDO(
                'mysql:host=' . config('database.connections.mysql.host') . 
                ';port=' . config('database.connections.mysql.port'),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                ]
            );
            
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}`");
            $this->info("Database '{$database}' created successfully!");
            
        } catch (\Exception $e) {
            $this->error("Failed to create database: " . $e->getMessage());
        }
    }
}