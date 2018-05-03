<h1><?=$model->name?></h1>
<div class="news-date"><?=Yii::$app->formatter->asDate($model->date, 'dd.MM.yyyy') ?></div>
<div class="news-image">
    <?
    echo Yii::$app->thumbnail->img(
            Yii::$app->params['pathFiles'].$model->image, [
                'thumbnail' => [
                    'width' => 450,
                    'height' => 450,
                ],
                'placeholder' => [
                    'width' => 320,
                    'height' => 230
                ]
            ]
        );
    ?>
</div>
<div class="news-content">
    <?=$model->content?>
</div>
