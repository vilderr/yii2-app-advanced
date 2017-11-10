<?php

use yii\db\Migration;

/**
 * Handles the creation of table `import_profile`.
 */
class m171103_214639_create_import_profile_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%import_profiles}}', [
            'id'           => $this->primaryKey(),
            'name'         => $this->string(255)->notNull(),
            'sections_url' => $this->string(255)->notNull(),
            'products_url' => $this->string(255)->notNull(),
            'api_key'      => $this->string(255)->notNull(),
            'sort'         => $this->integer()->defaultValue(500),
            'status'       => $this->integer(1)->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('{{%idx-import_profiles-name}}', '{{%import_profiles}}', 'name');
        $this->createIndex('{{%idx-import_profiles-sort}}', '{{%import_profiles}}', 'sort');
        $this->createIndex('{{%idx-import_profiles-status}}', '{{%import_profiles}}', 'status');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%import_profiles}}');
    }
}
