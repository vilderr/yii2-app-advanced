<?php

use yii\db\Migration;

/**
 * Handles the creation of table `catalog_products`.
 */
class m171101_211117_create_catalog_products_table extends Migration
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

        $this->createTable('{{%catalog_products}}', [
            'id'              => $this->primaryKey(),
            'category_id'     => $this->integer(),
            'name'            => $this->string()->notNull(),
            'xml_id'          => $this->string()->notNull(),
            'tmp_id'          => $this->string(),
            'description'     => $this->text(),
            'current_section' => $this->string(),
            'url'             => $this->string(),
            'created_at'      => $this->integer()->unsigned()->notNull(),
            'updated_at'      => $this->integer()->unsigned()->notNull(),
            'picture_id'      => $this->integer(),
            'price'           => $this->integer(),
            'old_price'       => $this->integer(),
            'discount'        => $this->integer(),
            'sort'            => $this->integer()->defaultValue(500),
            'status'          => $this->smallInteger(1)->defaultValue(1),
            'show_counter'    => $this->integer()->defaultValue(0)
        ], $tableOptions);

        $this->createIndex('{{%idx-catalog_products-xml_id}}', '{{%catalog_products}}', 'xml_id', true);
        $this->createIndex('{{%idx-catalog_products-category_id}}', '{{%catalog_products}}', 'category_id');
        $this->createIndex('{{%idx-catalog_products-status}}', '{{%catalog_products}}', 'status');

        $this->addForeignKey('{{%fk-catalog_products-category}}', '{{%catalog_products}}', 'category_id', '{{%catalog_categories}}', 'id', 'SET NULL');
        $this->addForeignKey('{{%fk-catalog_products-picture}}', '{{%catalog_products}}', 'picture_id','{{%pictures}}', 'id', 'SET NULL');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%catalog_products}}');
    }
}
