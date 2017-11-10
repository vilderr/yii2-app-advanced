<?php

use yii\db\Migration;

/**
 * Handles the creation of table `main_slider`.
 */
class m171109_112727_create_main_slider_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%main_slider}}', [
            'id'         => $this->primaryKey(),
            'picture_id' => $this->integer(),
            'text'       => $this->text(),
            'url'        => $this->string(),
            'sort'       => $this->integer()->defaultValue(500),
            'status'     => $this->smallInteger()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('{{%idx-main_slider-status}}', '{{%main_slider}}', 'status');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%main_slider}}');
    }
}
