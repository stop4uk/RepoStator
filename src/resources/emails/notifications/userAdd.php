<?php

/**
 * @var array $data
 */

use yii\bootstrap5\Html;
use yii\helpers\Url;

?>

<table class="s-4 w-full" role="presentation" style="width: 100%;">
    <tbody>
    <tr>
        <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" height="16">
            &#160;
        </td>
    </tr>
    </tbody>
</table>
<p class="" style="line-height: 24px; font-size: 16px; width: 100%; margin: 0; text-align: justify">
    <?= Yii::t('emails', "Уважаемый {userName}! Для Вашего Email адреса была создана учетная запись. Вы можете " .
        "авторизоваться в системе, нажав на кнопку ниже. В качестве данных для авторизации используйте этот Email и пароль " .
        "<strong>{password}</strong>. Пароль временный. Без его изменения после авторизации, работать в системе будет невозможно", [
        'userName' => $data['name'],
        'password' => $data['password'],
    ]); ?>
</p>
<table class="s-4 w-full" role="presentation" style="width: 100%;">
    <tbody>
    <tr>
        <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" height="16">
            &#160;
        </td>
    </tr>
    </tbody>
</table>
<table class="btn btn-primary p-3 fw-700" role="presentation" style="border-radius: 6px; border-collapse: separate !important; font-weight: 700 !important; width: 100%">
    <tbody>
    <tr>
        <td style="line-height: 24px; font-size: 16px; border-radius: 6px; font-weight: 700 !important; margin: 0; text-align:center">
            <?= Html::a(Yii::t('emails', 'Войти в систему'), Url::to(['signin'], true), ['style' => 'width: 100%; color: #ffffff; font-size: 16px; font-family: Helvetica, Arial, sans-serif; text-decoration: none; border-radius: 6px; line-height: 20px; display: block; font-weight: 700 !important; white-space: nowrap; background-color: #0d6efd; padding: 12px; border: 1px solid #0d6efd;']); ?>
        </td>
    </tr>
    </tbody>
</table>