<?php

use yii\db\Migration;

/**
 * Handles the creation of table `catalog_products_properties`.
 */
class m171102_164604_create_properties_table extends Migration
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

        $this->createTable('{{%properties}}', [
            'id'        => $this->primaryKey(),
            'name'      => $this->string(255)->notNull(),
            'slug'      => $this->string(255)->notNull(),
            'xml_id'    => $this->string(255)->notNull(),
            'sort'      => $this->integer()->defaultValue(500),
            'status'    => $this->smallInteger(1)->defaultValue(1),
            'filtrable' => $this->smallInteger(1)->defaultValue(0),
            'sef'       => $this->smallInteger(1)->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('idx-properties-slug', '{{%properties}}', 'slug', true);
        $this->createIndex('idx-properties-xml_id', '{{%properties}}', 'xml_id', true);
        $this->createIndex('idx-properties-status', '{{%properties}}', 'status');
        $this->createIndex('idx-properties-filtrable', '{{%properties}}', 'filtrable');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%properties}}');
    }
}
