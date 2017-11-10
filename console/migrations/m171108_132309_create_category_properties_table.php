<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category_properties`.
 */
class m171108_132309_create_category_properties_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category_properties}}', [
            'category_id' => $this->integer()->notNull(),
            'property_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-category_properties_property}}', '{{%category_properties}}', ['category_id', 'property_id']);

        $this->createIndex('{{%idx-category_properties-category_id}}', '{{%category_properties}}', 'category_id');
        $this->createIndex('{{%idx-category_properties-property_id}}', '{{%category_properties}}', 'property_id');

        $this->addForeignKey('{{%fk-category_properties-category_id}}', '{{%category_properties}}', 'category_id', '{{%catalog_categories}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-category_properties-property_id}}', '{{%category_properties}}', 'property_id', '{{%properties}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%category_properties}}');
    }
}
