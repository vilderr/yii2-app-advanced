<?php

use yii\db\Migration;

/**
 * Handles the creation of table `option`.
 */
class m171107_092442_create_option_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%options}}', [
            'entity' => $this->string()->notNull(),
            'name'   => $this->string()->notNull(),
            'value'  => $this->text()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-options}}', '{{%options}}', ['entity', 'name']);
        $this->createIndex('{{%idx-options-module_id-name}}', '{{%options}}', ['entity', 'name'], true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%options}}');
    }
}
