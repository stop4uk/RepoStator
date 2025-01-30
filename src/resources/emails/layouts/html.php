<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
            <title><?= Html::encode($this->title) ?></title>
            <style type="text/css">
                body,table,td{font-family:Helvetica,Arial,sans-serif !important}
                .ExternalClass{width:100%}
                .ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:150%}
                a{text-decoration:none}
                *{color:inherit}
                a[x-apple-data-detectors],u+#body a,#MessageViewBody a{color:inherit;text-decoration:none;font-size:inherit;font-family:inherit;font-weight:inherit;line-height:inherit}
                img{-ms-interpolation-mode:bicubic}
                table:not([class^=s-]){font-family:Helvetica,Arial,sans-serif;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0px;border-collapse:collapse}
                table:not([class^=s-])
                td{border-spacing:0px;border-collapse:collapse}
                @media screen and (max-width: 600px){
                    .w-full,.w-full>tbody>tr>td{width:100% !important}
                    .w-24,.w-24>tbody>tr>td{width:96px !important}
                    .w-40,.w-40>tbody>tr>td{width:160px !important}
                    .p-lg-10:not(table),.p-lg-10:not(.btn)>tbody>tr>td,.p-lg-10.btn td a{padding:0 !important}
                    .p-3:not(table),.p-3:not(.btn)>tbody>tr>td,.p-3.btn td a{padding:12px !important}
                    .p-6:not(table),.p-6:not(.btn)>tbody>tr>td,.p-6.btn td a{padding:24px !important}
                    *[class*=s-lg-]>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}
                    .s-4>tbody>tr>td{font-size:16px !important;line-height:16px !important;height:16px !important}
                    .s-6>tbody>tr>td{font-size:24px !important;line-height:24px !important;height:24px !important}
                    .s-10>tbody>tr>td{font-size:40px !important;line-height:40px !important;height:40px !important}
                }
            </style>
            <?php $this->head() ?>
        </head>
        <body class="bg-light" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;" bgcolor="#f7fafc">
            <?php $this->beginBody(); ?>
                <table class="bg-light body" valign="top" role="presentation" border="0" cellpadding="0" cellspacing="0" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;" bgcolor="#f7fafc">
                <tbody>
                    <tr>
                        <td valign="top" style="line-height: 24px; font-size: 16px; margin: 0;" align="left" bgcolor="#f7fafc">
                            <table class="container" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td align="center" style="line-height: 24px; font-size: 16px; margin: 0; padding: 0 16px;">
                                        <table align="center" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px; margin: 0 auto;">
                                        <tbody>
                                            <tr>
                                                <td style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
                                                    <table class="s-10 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                                <tbody>
                                                <tr>
                                                    <td style="line-height: 40px; font-size: 40px; width: 100%; height: 40px; margin: 0;" align="left" width="100%" height="40">
                                                        &#160;
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                                    <table class="card p-6 p-lg-10 space-y-4" role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-radius: 6px; border-collapse: separate !important; width: 100%; overflow: hidden; border: 1px solid #e2e8f0;" bgcolor="#ffffff">
                                                        <tbody>
                                                            <tr>
                                                                <td style="line-height: 24px; font-size: 16px; width: 100%; margin: 0; padding: 40px;" align="left" bgcolor="#ffffff">
                                                                    <?= $content; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="s-10 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                                                <tbody>
                                                <tr>
                                                    <td style="line-height: 40px; font-size: 40px; width: 100%; height: 40px; margin: 0;" align="left" width="100%" height="40">
                                                        &#160;
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                                    <?php if (Yii::$app->settings->get('template', 'footer_enable') ): ?>
                                                <table class="ax-center" role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                                    <tbody>
                                                    <tr>
                                                        <td style="line-height: 24px; font-size: 16px; margin: 0;" align="center">
                                                            <?= Yii::$app->settings->get('template', 'footer_name'); ?>
                                                        </td>
                                                    </tr>

                                                    <?php if (Yii::$app->settings->get('template', 'footer_year')): ?>
                                                        <tr>
                                                            <td style="line-height: 24px; font-size: 16px; margin: 0;" align="center">
                                                                <?= '&copy; ' . date('Y'); ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </td>
                    </tr>
                </tbody>
                </table>
            <?php $this->endBody(); ?>
        </body>
    </html>
<?php $this->endPage();
