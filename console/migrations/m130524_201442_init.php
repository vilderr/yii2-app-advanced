<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string()->notNull()->unique(),
            'name'                 => $this->string(255),
            'last_name'            => $this->string(255),
            'email'                => $this->string()->notNull()->unique(),
            'phone'                => $this->string()->notNull(),
            'created_at'           => $this->integer()->unsigned()->notNull(),
            'updated_at'           => $this->integer()->unsigned()->notNull(),
            'status'               => $this->smallInteger(1)->notNull()->defaultValue(1),
            'auth_key'             => $this->string(32)->notNull(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email_confirm_token'  => $this->string()->unique(),
        ], $tableOptions);

        $this->createIndex('{{%idx-users-username}}', '{{%users}}', 'username');
        $this->createIndex('{{%idx-users-email}}', '{{%users}}', 'email');
        $this->createIndex('{{%idx-users-phone}}', '{{%users}}', 'phone', true);
        $this->createIndex('{{%idx-users-status}}', '{{%users}}', 'status');
    }

    public function down()
    {
        $this->dropTable('{{%users}}');
    }
}
