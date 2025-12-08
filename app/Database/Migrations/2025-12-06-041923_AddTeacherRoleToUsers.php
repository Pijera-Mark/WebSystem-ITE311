<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeacherRoleToUsers extends Migration
{
    public function up()
    {
        // Add 'teacher' to the ENUM constraint for role column
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'instructor', 'admin', 'teacher') DEFAULT 'student'");
        
        // Update existing 'instructor' roles to 'teacher' for consistency
        $this->db->query("UPDATE users SET role = 'teacher' WHERE role = 'instructor'");
    }

    public function down()
    {
        // Revert back to original ENUM constraint
        $this->db->query("UPDATE users SET role = 'instructor' WHERE role = 'teacher'");
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('student', 'instructor', 'admin') DEFAULT 'student'");
    }
}
