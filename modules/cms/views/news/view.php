<?php
use yii\widgets\DetailView;

?>
<h1>Просмотр новости № <?=$model->id?></h1>
<?php
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        'description:html',
        'content:html',
        [
            'attribute' => 'date',
            'format' => ['date', 'Y-MM-d']
        ],
        'image',
        [
            'attribute' => 'created_at',
            'format' => ['date', 'Y-MM-d HH:mm:ss']
        ],
        [
            'attribute' => 'updated_at',
            'format' => ['date', 'Y-MM-d HH:mm:ss']
        ]
    ]
]);