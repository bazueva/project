<?php
use yii\grid\GridView;
use yii\helpers\{
    Url,
    Html
};
use yii\widgets\Pjax;
?>

<h1>Новости</h1>
<a class="btn btn-success" href="<?= Url::to(['news/create'])?>">Создать новость</a>
<?php
Pjax::begin(['id' => 'pjax-container']);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'contentOptions' => function ($model, $key, $index, $column) {
                return ['width' => '60px'];
            }
        ],
        'name',
        'slug',
        [
            'attribute' => 'date',
            'contentOptions' => function ($model, $key, $index, $column) {
                return ['width' => '100px'];
            },
        ],
        [
            'attribute' => 'image',
            'format' => 'raw',
            'value' => function($data) {
                return Html::img(Yii::$app->params['pathFiles']. $data->image, [
                        'style' => 'width:60px; height: 60px'
                ]);
            }
        ],
        [
            'attribute' => 'act',
            'value' => function ($data) {
                return $data->act ? 'Да' : 'Нет';
            }
        ],
        [
            'attribute' => 'created_at',
            'format' => ['date', 'Y-MM-d HH:mm:ss']
        ],
        [
            'attribute' => 'updated_at',
            'format' => ['date', 'Y-MM-d HH:mm:ss']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'template' => '{view} {update} {delete}{link}',
            'buttons' => [
                'delete' => function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-remove-sign"></span>', '#', [
                        'title' => 'Удалить',
                        'aria-label' => 'Удалить',
                        'onclick' => "
                                if (confirm('Вы уверены, что хотите удалить запись?')) {
                                    $.ajax('$url', {
                                        type: 'POST'
                                    }).done(function(data) {
                                        $.pjax.reload({container: '#pjax-container'});
                                    });
                                }
                                return false;
                            ",
                    ]);
                },
            ],
        ],
        [
            'format' => 'raw',
            'value' => function ($data) {
                return Html::a(
                'Смотреть на сайте',
                    Url::to('news/' . $data->slug),
                    ['target' => '_blank']);
            }
        ]
    ],

]);

Pjax::end();