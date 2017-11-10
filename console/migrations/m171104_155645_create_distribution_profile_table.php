<?php

use yii\db\Migration;

/**
 * Handles the creation of table `distribution_profile`.
 */
class m171104_155645_create_distribution_profile_table extends Migration
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

        $this->createTable('{{%distribution_profiles}}', [
            'id'          => $this->primaryKey(),
            'name'        => $this->string(255)->notNull(),
            'description' => $this->string(),
            'sort'        => $this->integer()->defaultValue(500),
            'status'      => $this->integer(1)->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('{{%idx-distribution_profiles-status}}','{{%distribution_profiles}}', 'status');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%distribution_profiles}}');
    }
}
