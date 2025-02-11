<?php

/**
 * @var array $data
 */

?>

<table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
    <tbody>
    <tr>
        <td style="line-height: 16px; font-size: 16px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">
            &#160;
        </td>
    </tr>
    </tbody>
</table>
<p class="" style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="justify">
    <?= Yii::t('emails', "Уважаемый {userName}! Регистрация успешно завершена. Теперь, Вы можете авторизоваться " .
        "используя логин и пароль, который был указан при регистрации. По умолчанию, Вам доступны стандартные права доступа. " .
        "В случае необходимости, Вы можете обратиться к администратору для их изменения", [
        'userName' => $data['name'],
    ]); ?>
</p>