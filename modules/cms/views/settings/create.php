<?
/**
 * @var $model object Settings
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<div class="row">
    <?php $form = ActiveForm::begin([
        'id' => 'setting-create',
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);
    echo $form->field($model, 'name_setting');
    echo $form->field($model, 'name');
    echo $form->field($model, 'value');
    ?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>