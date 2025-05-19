<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Login Page -->
<div id="login-page" class="login-container">
    <div class="login-card">
        <h3 class="text-center mb-4">CRM Login</h3>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>
        <input type="hidden" id="customer-id">
        <div class="mb-3">
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        </div>
        <div class="mb-3">
            <?= $form->field($model, 'password_hash')->passwordInput()->label("Password") ?>
        </div>
        <div class="mb-3">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>
        </div>

        <div class="form-group">
            <div>
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>