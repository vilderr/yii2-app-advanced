<?php

use yii\db\Migration;

/**
 * Handles the creation of table `catalog_products_tags_assignments`.
 */
class m171102_133543_create_products_tags_assignments_table extends Migration
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

        $this->createTable('{{%catalog_products_tags_assignments}}', [
            'product_id' => $this->integer()->notNull(),
            'tag_id'     => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-catalog_products_tags_assignments}}', '{{%catalog_products_tags_assignments}}', ['product_id', 'tag_id']);

        $this->createIndex('{{%idx-catalog_products_tags_assignments-product_id}}', '{{%catalog_products_tags_assignments}}', 'product_id');
        $this->createIndex('{{%idx-catalog_products_tags_assignments-tag_id}}', '{{%catalog_products_tags_assignments}}', 'tag_id');

        $this->addForeignKey('{{%fk-catalog_products_tags_assignments-product_id}}', '{{%catalog_products_tags_assignments}}', 'product_id', '{{%catalog_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-catalog_products_tags_assignments-tag_id}}', '{{%catalog_products_tags_assignments}}', 'tag_id', '{{%tags}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%catalog_products_tags_assignments}}');
    }
}
