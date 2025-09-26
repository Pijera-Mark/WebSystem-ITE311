<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubmissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quiz_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'answer'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'score'     => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'submitted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('quiz_id', 'quizzes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('submissions');
    }

    public function down()
    {
        $this->forge->dropTable('submissions');
    }
}
