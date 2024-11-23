<?php

namespace app\controllers\auth;

use Yii;
use yii\base\Exception;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\bootstrap5\ActiveForm;

use app\base\BaseController;
use app\services\AuthService;
use app\forms\auth\{
    LoginForm,
    RegisterForm
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\auth
 */
final class DefaultController extends BaseController
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
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'register'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
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

    public function actionLogin(): array|string|Response
    {
        $model = new LoginForm();

        if ( $this->request->isAjax && $model->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($this->request->post()) && $model->validate() ) {
            try {
                $this->service->login($model, $this->request);
                return $this->goHome();
            } catch (Exception $e) { $this->catchException($e); }
        }

        return $this->render('login', compact('model'));
    }

    public function actionRegister(): array|string|Response
    {
        if ( !Yii::$app->settings->get('auth', 'register_enableMain') ) {
            $this->setMessage('error', Yii::t('notifications', 'Самостоятельная регистрация выключена. Пожалуйста, обратитесь к Вашему администратору'));
            return $this->goHome();
        }

        $model = new RegisterForm();

        if ( $this->request->isAjax && $model->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($this->request->post()) && $model->validate() ) {
            try {
                $this->service->register($model, $this->request);
                $message = 'Регистрация успешно завершена. ';

                if ( !Yii::$app->settings->get('auth', 'login_withoutVerification') ) {
                    $message .= 'Пожалуйста, перейдите по ссылке в письме для подтверждения Email адреса';
                }

                $this->setMessage('success', Yii::t('notifications', $message));
                return $this->goHome();
            } catch (Exception $e) { $this->catchException($e); }
        }

        return $this->render('register', compact('model'));
    }

    public function actionLogout()
    {
        try {
            $this->service->logout();
            return $this->goHome();
        } catch (Exception $e) { $this->catchException($e); }
    }
}