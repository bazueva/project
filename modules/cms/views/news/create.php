<?
/**
 * @var $model object News
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use dosamigos\tinymce\TinyMce;
use dosamigos\datepicker\DatePicker;
use dosamigos\switchinput\SwitchBox;
use dosamigos\fileinput\BootstrapFileInput;

//вынести в модель
if ($model->image) {
    $initialPreview = Html::img(\yii\helpers\Url::base().'/uploads/'.$model->image, ['width' => '200px', 'height' => '200px']);
} else {
    $initialPreview = '';
}
?>
<div class="row">
        <?php $form = ActiveForm::begin([
                'id' => 'news-create',
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
            ]);
            echo $form->field($model, 'name');
            echo $form->field($model, 'description')->widget(TinyMce::className(), [
                'options' => ['rows' => 6],
                'language' => 'ru',
                'clientOptions' => [
                    'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | 
                    bullist numlist outdent indent | link image'
                ]
            ]);
            echo $form->field($model, 'content')->widget(TinyMce::className(), [
                'options' => ['rows' => 12],
                'language' => 'ru',
                'clientOptions' => [
                    'plugins' => [
                        'advlist autolink lists link charmap  print hr preview pagebreak',
                        'searchreplace wordcount textcolor visualblocks visualchars code fullscreen nonbreaking',
                        'save insertdatetime media table contextmenu template paste image'
                    ],
                    'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
                ]
            ]);
            echo $form->field($model, 'date')->widget(DatePicker::className(), [
                    'inline' => true,
                    // modify template for custom rendering
                    'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);
            echo $form->field($model, 'image')->widget(BootstrapFileInput::className(), [
                'options' => ['accept' => 'image/*', 'multiple' => false],
                'clientOptions' => [
                    'showUpload' => false,
                    'initialPreview' => $initialPreview
                ],
                'clientEvents' => [
                    'filecleared' => 'function(e, params){
                        $("#news-delete_image").attr("checked", true);
                    }'
                ]
            ]);
            echo $form->field($model, 'delete_image')->checkbox([
                    'style' => 'display:none',
                    'label' => ''
                ], false);
            echo $form->field($model,'act')->widget(SwitchBox::className(), [
                'clientOptions' => [
                    'size' => 'large',
                    'onColor' => 'success',
                    'offColor' => 'danger'
                ]
            ]);
            ?>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
            <? ActiveForm::end(); ?>
</div>


