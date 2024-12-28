<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestDatabaseConnection extends Command
{
    protected $signature = 'db:test';
    protected $description = 'Test database connection';

    public function handle()
    {
        try {
            $pdo = \DB::connection()->getPdo();
            $this->info("Database connected successfully!");
            $this->info("Database: " . \DB::connection()->getDatabaseName());
        } catch (\Exception $e) {
            $this->error("Failed to connect to the database.");
            $this->error("Error: " . $e->getMessage());
        }
    }
}