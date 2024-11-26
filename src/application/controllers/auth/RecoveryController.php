<?php

namespace app\controllers\auth;

use app\components\base\BaseController;
use app\forms\auth\RecoveryForm;
use app\services\AuthBaseService;
use Yii;
use yii\base\Exception;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\auth
 */
final class RecoveryController extends BaseController
{
    public function __construct(
                                         $id,
                                         $module,
        private readonly AuthBaseService $service,
                                         $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action): bool
    {
        $this->layout = 'clear';

        return parent::beforeAction($action);
    }

    public function actionIndex(): array|string|Response
    {
        $model = new RecoveryForm();

        if ( $this->request->isAjax && $model->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($this->request->post()) && $model->validate() ) {
            try {
                $this->service->recovery($model, $this->request);

                $this->setMessage('success', Yii::t('notifications', 'На Вашу почту отправлена инструкция по сбросу пароля и восстановления доступа. Пожалуйста, следуйте ей'));
                return $this->goHome();
            } catch (Exception $e) { $this->catchException($e); }
        }

        return $this->render('index', compact('model'));
    }

    public function actionProcess(string $key): array|string|Response
    {
        $model = new RecoveryForm($key, ['scenario' => RecoveryForm::SCENARIO_PROCESS]);

        if ( $this->request->isAjax && $model->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($this->request->post()) && $model->validate() ) {
            try {
                $this->service->recoveryProcess($model);

                $this->setMessage('success', Yii::t('notifications', 'Пароль сброшен. Доступ восстановлен. Пожалуйста, авторизуйтесь'));
                return $this->redirect(['/login']);
            } catch (Exception $e) { $this->catchException($e); }
        }

        return $this->render('process', compact('model'));
    }
}