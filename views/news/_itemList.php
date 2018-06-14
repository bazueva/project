<?php

use yii\helpers\{
    Html,
    Url
};
?>

<div class="col-lg-4">
    <div class="news-name">
        <a href="<?=Url::to([$model->slug])?>"><?= $model->name ?></a>
    </div>
    <?php
    echo Yii::$app->thumbnail->img(
             Yii::$app->params['pathFiles'].$model->image, [
                'thumbnail' => [
                    'width' => 320,
                    'height' => 230,
                ],
                'placeholder' => [
                    'width' => 320,
                    'height' => 230
                ]
             ]
         );
    ?>
    <?= Yii::$app->formatter->asDate($model->date, 'dd.MM.yyyy') ?>
    <?= $model->description ?>
    <p>
        <a class="btn btn-default" href="<?= Url::to([$model->slug])?>">Читать &raquo;</a>
    </p>
</div>