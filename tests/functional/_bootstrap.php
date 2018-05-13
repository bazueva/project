<?php

namespace yii\web {
// add unit testing specific bootstrap code here
    function move_uploaded_file($tempFile, $destination)
    {
        return copy($tempFile, \Yii::$app->basePath . '/web/' . $destination);
    }
}
