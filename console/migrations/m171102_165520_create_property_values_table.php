<?php

use yii\db\Migration;

class m171102_165520_create_property_values_table extends Migration
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

        $this->createTable('{{%property_values}}', [
            'id'          => $this->primaryKey(),
            'property_id' => $this->integer()->notNull(),
            'name'        => $this->string(255)->notNull(),
            'slug'        => $this->string(255)->notNull(),
            'xml_id'      => $this->string(255)->notNull(),
            'sort'        => $this->integer()->defaultValue(500),
            'status'      => $this->integer(1)->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('{{%idx-property_values-property_id}}', '{{%property_values}}', 'property_id');
        $this->createIndex('{{%idx-property_values-status}}', '{{%property_values}}', 'status');

        $this->addForeignKey('{{%fk-property_values-property_id}}', '{{%property_values}}', 'property_id', '{{%properties}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%property_values}}');
    }
}
