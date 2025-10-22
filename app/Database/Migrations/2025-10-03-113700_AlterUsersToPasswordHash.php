<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersToPasswordHash extends Migration
{
    public function up()
    {
        // Ensure users table exists before altering
        $tableCheck = $this->db->query("SHOW TABLES LIKE 'users'");
        if (! $tableCheck || $tableCheck->getNumRows() === 0) {
            return;
        }

        // Helper to check if a column exists
        $columnExists = function (string $column): bool {
            $query = $this->db->query("SHOW COLUMNS FROM `users` LIKE '{$column}'");
            return $query && $query->getNumRows() > 0;
        };

        // Rename password -> password_hash if needed, or add password_hash if missing
        if (! $columnExists('password_hash') && $columnExists('password')) {
            $this->db->query("ALTER TABLE `users` CHANGE COLUMN `password` `password_hash` VARCHAR(255) NOT NULL");
        } elseif (! $columnExists('password_hash')) {
            $this->db->query("ALTER TABLE `users` ADD COLUMN `password_hash` VARCHAR(255) NOT NULL AFTER `email`");
        }

        // Ensure role enum matches application expectations
        if ($columnExists('role')) {
            // Try to set to ENUM('student','instructor','admin') with default 'student'
            $this->db->query("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('student','instructor','admin') NOT NULL DEFAULT 'student'");
        }
    }

    public function down()
    {
        $tableCheck = $this->db->query("SHOW TABLES LIKE 'users'");
        if (! $tableCheck || $tableCheck->getNumRows() === 0) {
            return;
        }

        $columnExists = function (string $column): bool {
            $query = $this->db->query("SHOW COLUMNS FROM `users` LIKE '{$column}'");
            return $query && $query->getNumRows() > 0;
        };

        // Revert password_hash -> password if original column is missing
        if ($columnExists('password_hash') && ! $columnExists('password')) {
            $this->db->query("ALTER TABLE `users` CHANGE COLUMN `password_hash` `password` VARCHAR(255) NOT NULL");
        }

        // Optionally relax role type back to VARCHAR(50) default 'user'
        if ($columnExists('role')) {
            $this->db->query("ALTER TABLE `users` MODIFY COLUMN `role` VARCHAR(50) NOT NULL DEFAULT 'user'");
        }
    }
}


