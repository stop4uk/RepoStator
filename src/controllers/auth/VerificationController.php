<?php

namespace app\controllers\auth;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\bootstrap5\ActiveForm;

use app\base\BaseController;
use app\services\AuthService;
use app\forms\auth\VerificationForm;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\auth
 */
final class VerificationController extends BaseController
{
    public function __construct(
        $id,
        $module,
        private readonly AuthService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'process'],
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
        $model = new VerificationForm();

        if ( $this->request->isAjax && $model->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($this->request->post()) && $model->validate() ) {
            try {
                $this->service->verification($model, $this->request);

                $this->setMessage('success', Yii::t('notifications', 'Для подтверждения Email, перейдите пожалуйста по ссылке, отправленной Вам в письме'));
                return $this->goHome();
            } catch (Exception $e) { $this->catchException($e); }
        }

        return $this->render('index', compact('model'));
    }

    public function actionProcess(string $key): Response|string
    {
        try {
            $this->service->verificationProcess($key);

            $this->setMessage('success', Yii::t('notifications', 'Ваш Email успешно подтвержден. Можете авторизоваться'));
            return $this->redirect(['/login']);
        } catch (Exception $e) { $this->catchException($e); }

        return $this->redirect(['/']);
    }

    public function actionChange(string $key)
    {
        try {
            $this->service->changeEmail($key);

            $this->setMessage('success', Yii::t('notifications', 'Ваш Email изменен'));
            return $this->redirect([Yii::$app->getUser()->id ? '/profile' : '/login']);
        } catch (Exception $e) { $this->catchException($e); }
    }
}