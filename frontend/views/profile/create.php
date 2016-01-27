<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Profile */

$this->title = 'Создание профиля';
$this->params['breadcrumbs'][] = ['label' => 'Учетная запись', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
