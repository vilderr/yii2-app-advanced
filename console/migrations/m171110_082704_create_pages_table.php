<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pages`.
 */
class m171110_082704_create_pages_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%pages}}', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(255)->notNull(),
            'title'      => $this->string(255)->notNull(),
            'slug'       => $this->string(255)->notNull(),
            'content'    => 'MEDIUMTEXT',
            'meta_json'  => 'JSON NOT NULL',
            'sort'       => $this->integer()->defaultValue(100),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status'     => $this->integer(1)->defaultValue(1),
            'lft'        => $this->integer()->notNull(),
            'rgt'        => $this->integer()->notNull(),
            'depth'      => $this->integer()->notNull(),
            'tree'       => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-pages-slug', '{{%pages}}', 'slug', true);
        $this->createIndex('idx-pages-status', '{{%pages}}', 'status');
    }

    public function down()
    {
        $this->dropTable('{{%pages}}');
    }
}
