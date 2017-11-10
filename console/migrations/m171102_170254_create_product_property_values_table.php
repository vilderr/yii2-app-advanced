<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_property_values`.
 */
class m171102_170254_create_product_property_values_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%product_property_values}}', [
            'product_id'  => $this->integer()->notNull(),
            'property_id' => $this->integer()->notNull(),
            'value_id'    => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-product_property_values}}', '{{%product_property_values}}', ['product_id', 'property_id', 'value_id']);

        $this->createIndex('{{%idx-product_property_values-product_id}}', '{{%product_property_values}}', 'product_id');
        $this->createIndex('{{%idx-product_property_values-property_id}}', '{{%product_property_values}}', 'property_id');

        $this->addForeignKey('{{%fk-product_property_values-product_id}}', '{{%product_property_values}}', 'product_id', '{{%catalog_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-product_property_values-property_id}}', '{{%product_property_values}}', 'property_id', '{{%properties}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%product_property_values}}');
    }
}
