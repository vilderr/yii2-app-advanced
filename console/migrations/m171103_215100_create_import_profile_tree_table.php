<?php

use yii\db\Migration;

/**
 * Handles the creation of table `import_profile_tree`.
 */
class m171103_215100_create_import_profile_tree_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%import_profile_tree}}', [
            'id'         => $this->primaryKey(),
            'profile_id' => $this->integer()->notNull(),
            'element_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-import_tree-profile_id', '{{%import_profile_tree}}', 'profile_id');
        $this->createIndex('idx-import_tree-element_id', '{{%import_profile_tree}}', 'element_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%import_profile_tree}}');
    }
}
