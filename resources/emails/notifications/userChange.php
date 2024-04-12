<?php

/**
 * @var array $data
 */

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
    <?= Yii::t('emails', "Уважаемый {userName}! Ваша учетная запись была изменена администратором", [
        'userName' => $data['name']
    ]); ?>
</p>
<hr />
<p class="" style="line-height: 24px; font-size: 16px; width: 100%; margin: 0; text-align: justify">
    <?php foreach ($data as $key => $value) {
        switch ($key) {
            case 'password':
                echo Yii::t('emails', "Новый пароль: <strong>{password}</strong><br />", ['password' => $value]);
                break;
            case 'email':
                echo Yii::t('emails', "Новый email: <strong>{email}</strong><br />", ['email' => $value]);
                break;
            case 'account_status':
                echo Yii::t('emails', "Новый статус: <strong>{account_status}</strong><br />", ['account_status' => $value]);
                break;
            case 'group':
                echo Yii::t('emails', "Новая группа: <strong>{group}</strong><br />", ['group' => $value]);
                break;
        }
    } ?>
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