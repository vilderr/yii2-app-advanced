<?php

use yii\db\Migration;

/**
 * Handles the creation of table `distribution_profile_parts`.
 */
class m171104_160002_create_distribution_profile_parts_table extends Migration
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

        $this->createTable('{{%distribution_profile_parts}}', [
            'id'          => $this->primaryKey(),
            'profile_id'  => $this->integer()->notNull(),
            'name'        => $this->string()->notNull(),
            'filter_json' => $this->text(),
            'action_json' => $this->text(),
            'sort'        => $this->integer()->defaultValue(500),
            'status'      => $this->smallInteger(1)->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('{{%idx-distribution_profile_parts-profile_id}}', '{{%distribution_profile_parts}}', 'profile_id');
        $this->addForeignKey('{{%fk-distribution_profile_parts-profile_id}}', '{{%distribution_profile_parts}}', 'profile_id', '{{%distribution_profiles}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%distribution_profile_parts}}');
    }
}
