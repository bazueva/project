<?php
use yii\widgets\ListView;

?>
    <div class="body-content">
        <div class="row">
            <h1><?=$this->title?></h1>
            <?
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_itemList'
            ]); ?>
        </div>
    </div>
