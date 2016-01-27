<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model frontend\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => 45])?>
    <?= $form->field($model, 'second_name')->textInput(['maxlength' => 45])?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => 45])?>
    <br>
    <?php echo $form->field($model,'birthdate')->widget(DatePicker::className(),
        [   'language' => 'ru',
            'dateFormat' => 'php:d-m-Y',
            'clientOptions' => [
                'yearRange' => '-115:+0',
                'changeYear' => 'true',
                'changeMonth'=> 'true',
                ]
    ]) ?>
    <br>

    <?= $form->field($model, 'gender_id')->dropDownList($model->genderList, ['promt'=> 'Выберите пол']);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
