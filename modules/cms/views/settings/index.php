<?php
use yii\grid\GridView;
use yii\helpers\Url;
?>
<h1>Настройки</h1>
<a class="btn btn-success" href="<?= Url::to(['settings/create'])?>">Создать настройку</a>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'id',
            'contentOptions' => function ($model, $key, $index, $column) {
                return ['width' => '60px'];
            }
        ],
        'name_setting',
        'name',
        'value',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'template' => '{update} {link}',
        ],
    ],
]);
