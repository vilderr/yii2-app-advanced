<?php

use yii\db\Migration;

/**
 * Handles the creation of table `import_profile_sections`.
 */
class m171103_214939_create_import_profile_sections_table extends Migration
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

        $this->createTable('{{%import_profile_sections}}', [
            'profile_id' => $this->integer()->notNull(),
            'value'      => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-import_profile_sections}}', '{{%import_profile_sections}}', ['profile_id', 'value']);
        $this->createIndex('{{%idx-import_profile_sections-profile_id}}', '{{%import_profile_sections}}', 'profile_id');
        $this->addForeignKey('{{%fk-import_profile_sections-profile_id}}', '{{%import_profile_sections}}', 'profile_id', '{{%import_profiles}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%import_profile_sections}}');
    }
}
