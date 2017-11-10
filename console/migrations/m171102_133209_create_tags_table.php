<?php

use yii\db\Migration;

/**
 * Handles the creation of table `catalog_products_tags`.
 */
class m171102_133209_create_tags_table extends Migration
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

        $this->createTable('{{%tags}}', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(255)->notNull(),
            'slug'       => $this->string()->notNull()
        ], $tableOptions);

        $this->createIndex('{{%idx-tags-slug}}', '{{%tags}}', 'slug', true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%tags}}');
    }
}
