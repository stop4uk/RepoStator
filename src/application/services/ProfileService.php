<?php

namespace app\services;

use app\components\base\BaseService;
use app\components\events\objects\ProfileEvent;
use app\entities\user\{UserEmailchangeEntity, UserEntity};
use app\forms\user\{UserEmailChangeForm, UserPasswordChangeForm};
use app\helpers\CommonHelper;
use app\repositories\user\UserBaseRepository;
use Yii;
use yii\base\Exception;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\services
 */
final class ProfileService extends BaseService
{
    const EVENT_AFTER_CHANGEEMAIL = 'profile.afterChangeEmail';

    public function changeEmail(UserEmailChangeForm $form): bool
    {
        $entity = new UserEmailchangeEntity();
        $entity->email = $form->email;
        $entity->key = Yii::$app->getSecurity()->generateRandomString(32);

        $user = UserBaseRepository::get(Yii::$app->getUser()->id);
        if (CommonHelper::saveAttempt($entity, 'Users.InitialData')) {
            $this->trigger(self::EVENT_AFTER_CHANGEEMAIL, new ProfileEvent([
                'userName' => $user->shortName,
                'email' => $entity->email,
                'key' => $entity->key,
            ]));

            return true;
        }

        throw new Exception(Yii::t('exceptions', 'При подаче заявки на смену Email возникли ошибки. Пожалуйста, обратитесь к администратору'));
    }

    public function changeEmailCancel(int $id): bool
    {
        $entity = UserEmailchangeEntity::find()
            ->where([
                'id' => $id
            ])
            ->limit(1)
            ->one();

        $entity->updated_at = time();
        if ( $entity->softDelete() ) {
            return true;
        }

        Yii::error($entity->getErrors(), 'Users.InitialData');
        throw new Exception(Yii::t('exceptions', 'При отмене заявки на смену Email произошла ошибка. Пожалуйста, обратитесь к администратору'));
    }

    public function changePassword(UserPasswordChangeForm $form, UserEntity $entity): bool
    {
        $entity->scenario = $entity::SCENARIO_CHANGE_PASSWORD;
        $entity->password = $form->password;
        $entity->account_cpass_required = 0;
        $entity->recordAction($form);

        if (CommonHelper::saveAttempt($entity, 'Users.InitialData')) {
            return true;
        }

        throw new Exception(Yii::t('exceptions', 'При обновлении пароля возникли ошибки. Пожалуйста, обратитесь к администратору'));
    }
}