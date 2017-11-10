<?php

use yii\db\Migration;

/**
 * Handles the creation of table `picture`.
 */
class m171101_190416_create_picture_table extends Migration
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

        $this->createTable('{{%pictures}}', [
            'id'         => $this->primaryKey(),
            'model_name' => $this->string()->notNull(),
            'file'       => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-pictures-model_name}}', '{{%pictures}}', 'model_name');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%pictures}}');
    }
}
