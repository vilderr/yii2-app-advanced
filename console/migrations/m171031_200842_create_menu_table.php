<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171031_200842_create_menu_table extends Migration
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

        $this->createTable('{{%backend_menu}}', [
            'id'        => $this->primaryKey(),
            'name'      => $this->string(255)->notNull(),
            'route'     => $this->string(255),
            'icon'      => $this->string(255),
            'sort'      => $this->integer()->defaultValue(100),
            'parent_id' => $this->integer()->defaultValue(0),
            'lft'       => $this->integer()->notNull(),
            'rgt'       => $this->integer()->notNull(),
            'depth'     => $this->integer()->notNull(),
            'tree'      => $this->integer(),
        ], $tableOptions);

        $this->createIndex('{{%idx-backend_menu-lft}}', '{{%backend_menu}}', ['tree', 'lft', 'rgt']);
        $this->createIndex('{{%idx-backend_menu-rgt}}', '{{%backend_menu}}', ['tree', 'rgt']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%backend_menu}}');
    }
}
