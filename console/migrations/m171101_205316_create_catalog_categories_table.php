<?php

use yii\db\Migration;

/**
 * Handles the creation of table `catalog_categories`.
 */
class m171101_205316_create_catalog_categories_table extends Migration
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

        $this->createTable('{{%catalog_categories}}', [
            'id'            => $this->primaryKey(),
            'name'          => $this->string()->notNull(),
            'slug'          => $this->string()->notNull(),
            'slug_path'     => $this->string()->unique(),
            'xml_id'        => $this->string(),
            'description'   => $this->text(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
            'picture_id'    => $this->integer(),
            'sort'          => $this->integer()->defaultValue(500),
            'status'        => $this->smallInteger(1)->defaultValue(1),
            'global_status' => $this->smallInteger(1)->defaultValue(1),
            'meta_json'     => 'JSON NOT NULL',
            'category_id'   => $this->integer(),
            'lft'           => $this->integer()->notNull(),
            'rgt'           => $this->integer()->notNull(),
            'depth'         => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-catalog_categories-slug}}', '{{%catalog_categories}}', 'slug');
        $this->createIndex('{{%idx-catalog_categories-slug_path}}', '{{%catalog_categories}}', 'slug_path', true);
        $this->createIndex('{{%idx-catalog_categories-lft}}', '{{%catalog_categories}}', ['lft', 'rgt']);
        $this->createIndex('{{%idx-catalog_categories-rgt}}', '{{%catalog_categories}}', ['rgt']);
        $this->createIndex('{{%idx-catalog_categories-global_status}}', '{{%catalog_categories}}', 'global_status');

        $this->addForeignKey('{{%fk-catalog_categories-picture}}', '{{%catalog_categories}}', 'picture_id', '{{%pictures}}', 'id', 'SET NULL');

        $this->insert('{{%catalog_categories}}', [
            'id'            => 1,
            'name'          => Yii::t('app', 'Top level'),
            'slug'          => 'root',
            'created_at'    => time(),
            'updated_at'    => time(),
            'sort'          => 500,
            'status'        => 1,
            'global_status' => 1,
            'meta_json' => '{}',
            'lft'           => 1,
            'rgt'           => 2,
            'depth'         => 0,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%catalog_categories}}');
    }
}
