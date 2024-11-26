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
    <?= Yii::t('emails', "Уважаемый {userName}! Запрошенный Вами отчет <strong>{templateName}</strong> за период расчета " .
        "<strong>{period}</strong> успешно сфоримрован! Скачать отчет можно, если перейти по <strong>{link}</strong>", [
        'userName' => $data['job']->user->shortName,
        'templateName' => $data['template']->name,
        'period' => $data['period'],
        'link' => Html::a(Yii::t('emails', 'этой ссылке'), Url::to(['/download', 'path' => base64_encode($data['job']->file)], true))
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