<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PermissionHelpers;

/* @var $this yii\web\View */
/* @var $model frontend\models\Profile */

"Профайл ". $this->title = $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Учетная запись', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (PermissionHelpers::userMustBeOwner('profile', $model->id)) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }?>
        <?= Html::a('Delete', ['delete', 'id'=>$model->id], [
            'class' => 'btn btn-danger',
            'data' => ['confirm'=> Yii::t('app','Вы действительно хотите это удалить?'),
                'method' => 'post',
                ],
            ])?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'user.username',
            'first_name',
            //'second_name:ntext',
            'last_name:ntext',
            'birthdate',
            'gender.gender_name',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
