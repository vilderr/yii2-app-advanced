<?php

use backend\widgets\TreeCheckboxList;
use common\collects\catalog\import\ImportProfileCollection as Collection;

/**
 * @var $form           \yii\widgets\ActiveForm
 * @var $collection     Collection
 * @var $remoteSections array
 * @var $tab            string
 */

?>
<?= $form->field($collection->sections, 'values')->widget(TreeCheckboxList::className(), [
    'values' => $remoteSections,
]); ?>