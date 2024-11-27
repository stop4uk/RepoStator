<?php

namespace app\useCases\users\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\{
    NotFoundHttpException,
    Response
};
use yii\data\ArrayDataProvider;
use yii\bootstrap5\ActiveForm;

use app\components\base\BaseController;
use app\useCases\users\{
    entities\user\UserEntity,
    forms\user\UserEmailChangeForm,
    forms\user\UserPasswordChangeForm,
    models\user\ProfileModel,
    repositories\user\UserRepository,
    services\ProfileService
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers
 */
final class ProfileController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'except' => ['download'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function __construct(
        $id,
        $module,
        private readonly ProfileService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $entity = $this->findEntity();
        $entity->scenario = UserEntity::SCENARIO_UPDATE;

        $model = new ProfileModel($entity);

        $userEmailChangeForm = new UserEmailChangeForm();
        $userPasswordChangeForm = new UserPasswordChangeForm();

        $emailchangesDataProvider = new ArrayDataProvider([
            'allModels' => $entity->emailChanges
        ]);

        if ( $this->request->isAjax && $model->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($this->request->post()) && $model->validate() ) {
            try {
                $this->service->save(
                    model: $model,
                    categoryForLog: 'Users.Profile',
                    errorMessage: Yii::t('exceptions', 'При обновлении профиля возникли ошибки. Пожалуйста, обратитесь к администратору')
                );

                $this->setMessage('success', Yii::t('notifications', 'Ваши данные обновлены'));
                return $this->refresh();
            } catch (Exception $e) { $this->catchException($e); }
        }

        return $this->render('index', compact('model', 'userEmailChangeForm', 'userPasswordChangeForm', 'emailchangesDataProvider'));
    }

    public function actionChangeemail()
    {
        $form = new UserEmailChangeForm();

        if ( $this->request->isAjax && $form->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        if ( $form->load($this->request->post()) && $form->validate() ) {
            try {
                $this->service->changeEmail($form);

                $this->setMessage('success', Yii::t('notifications', 'Заявка на смену Email успешно подана. Проверьте указанную почту'));
                return $this->redirect(['/profile']);
            } catch (Exception $e) { $this->catchException($e); }
        }
    }

    public function actionChangeemailcancel(int $id)
    {
        $this->response->format = Response::FORMAT_JSON;

        try {
            $this->service->changeEmailCancel($id);

            return [
                'status' => 'success',
                'message' => Yii::t('notifications', 'Заявка отменена')
            ];
        } catch (Exception $e) { return $this->catchException($e, false); }
    }

    public function actionChangepassword()
    {
        $this->layout='clear';
        $form = new UserPasswordChangeForm();

        if ( $this->request->isAjax && $form->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        if ( $form->load($this->request->post()) && $form->validate() ) {
            $entity = $this->findEntity(true);

            try {
                $this->service->changePassword(
                    form: $form,
                    entity: $entity
                );

                $this->setMessage('success', Yii::t('notifications', 'Пароль успешно изменен'));
                return $this->redirect(['/profile']);
            } catch (Exception $e) { $this->catchException($e); }
        }

        return $this->render('_partial/changePass', [
            'userPasswordChangeForm' => $form
        ]);
    }

    private function findEntity(bool $withOutRelations = false)
    {
        $query = UserRepository::get(Yii::$app->getUser()->id, $withOutRelations
            ? []
            : ['rights', 'sessions', 'lastAuth', 'emailChanges']
        );

        if ( $query !== null) {
            return $query;
        }

        throw new NotFoundHttpException(Yii::t('exceptions', 'Данные не найдены'));
    }
}
