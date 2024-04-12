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
    <?= Yii::t('emails', "Уважаемый {userName}! Ваша учетная запись была удалена. Дальнейшая работа с системой " .
        "для Вас невозможна", ['userName' => $data['name']]); ?>
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