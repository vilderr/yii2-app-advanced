<?php

use yii\db\Migration;

class m171030_161249_add_user_roles extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%auth_items}}', ['type', 'name', 'description'], [
            [1, 'user', Yii::t('app', 'Users')],
            [1, 'admin', Yii::t('app', 'Administrators')],
        ]);

        $this->batchInsert('{{%auth_item_children}}', ['parent', 'child'], [
            ['admin', 'user'],
        ]);

        $this->execute('INSERT INTO {{%auth_assignments}} (item_name, user_id) SELECT \'user\', u.id FROM {{%users}} u ORDER BY u.id');
    }

    public function down()
    {
        $this->delete('{{%auth_items}}', ['name' => ['user', 'admin']]);
    }
}
