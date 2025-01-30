<?php

namespace app\modules\users\entities;

use Yii;
use yii\base\Model;
use yii\behaviors\{
    BlameableBehavior,
    TimestampBehavior
};
use yii\db\ActiveQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

use app\components\base\{
    BaseModel,
    BaseAR
};
use app\modules\users\helpers\UserHelper;

/**
 * @property string $email
 * @property string $password
 * @property string $lastname
 * @property string $firstname
 * @property string|null $middlename
 * @property int|null $phone
 * @property int $account_status
 * @property string $account_key
 * @property int|null $account_cpass_required
 * @property int $created_at
 * @property int|null $created_uid
 * @property int|null $updated_at
 * @property int|null $updated_uid
 * @property int|null $blocked_at
 * @property int|null $blocked_uid
 * @property string|null $blocked_comment
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\entities
 */
final class UserEntity extends BaseAR
{
    const SCENARIO_UPDATE_BY_ADMIN = 'edit_admin';
    const SCENARIO_CHANGE_PASSWORD = 'change_password';
    const SCENARIO_CHANGE_EMAIL = 'change_email';

    const STATUS_WAITCONFIRM = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

    public const STATUSES = [
        self::STATUS_WAITCONFIRM,
        self::STATUS_ACTIVE,
        self::STATUS_BLOCKED,
    ];

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => time(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_uid'],
                    self::EVENT_BEFORE_UPDATE => ['updated_uid'],
                ],
            ],
            [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'record_status' => self::RSTATUS_DELETED
                ],
            ]
        ];
    }

    public function scenarios(): array
    {
        return [
            self::SCENARIO_DEFAULT => [],
            self::SCENARIO_CHANGE_RECORD_STATUS => ['record_status'],
            self::SCENARIO_INSERT => ['email', 'password', 'lastname', 'firstname', 'middlename', 'phone', 'account_status', 'account_cpass_required'],
            self::SCENARIO_UPDATE => ['lastname', 'firstname', 'middlename', 'phone'],
            self::SCENARIO_UPDATE_BY_ADMIN => ['email', 'password', 'lastname', 'firstname', 'middlename', 'phone', 'account_status', 'account_cpass_required'],
            self::SCENARIO_CHANGE_PASSWORD => ['password', 'account_cpass_required'],
            self::SCENARIO_CHANGE_EMAIL => ['email']
        ];
    }

    public function recordAction(BaseModel|Model $model): void
    {
        $password = $this->password;

        if (in_array($this->scenario, [self::SCENARIO_UPDATE_BY_ADMIN, self::SCENARIO_CHANGE_PASSWORD])) {
            if ($this->scenario == self::SCENARIO_UPDATE_BY_ADMIN) {
                $password = (!$model->password) ? $this->password : UserHelper::generatePassword($model->password);
                if ($model->password) {
                    $this->account_key = Yii::$app->getSecurity()->generateRandomString();
                }
            } else {
                $password = UserHelper::generatePassword($model->password);
                $this->account_key = Yii::$app->getSecurity()->generateRandomString();
            }
        }

        if ($this->scenario == self::SCENARIO_INSERT) {
            $password = UserHelper::generatePassword($model->password);
            $this->account_key = Yii::$app->getSecurity()->generateRandomString(32);
        }

        $this->setAttributes($model->toArray());
        $this->password = $password;

        if (!$this->phone) {
            $this->phone = NULL;
        }
    }

    public function attributeLabels(): array
    {
        return UserHelper::labels();
    }

    public function getShortName(): string
    {
        return UserHelper::getShortName($this->toArray(['lastname', 'firstname', 'middlename']));
    }

    public function getFullName(): string
    {
        return UserHelper::getFullName($this->toArray(['lastname', 'firstname', 'middlename']));
    }

    public function getGroup(): ActiveQuery
    {
        return $this->hasOne(UserGroupEntity::class, ['user_id' => 'id'])
            ->andFilterWhere(['record_status' => BaseAR::RSTATUS_ACTIVE]);
    }

    public function getRights(): ActiveQuery
    {
        return $this->hasMany(UserRightEntity::class, ['user_id' => 'id']);
    }

    public function getLastAuth(): ActiveQuery
    {
        return $this->hasOne(UserSessionEntity::class, ['user_id' => 'id'])->orderBy('id DESC')->limit(1);
    }

    public function getSessions(): ActiveQuery
    {
        return $this->hasMany(UserSessionEntity::class, ['user_id' => 'id'])->limit(20)->orderBy('id DESC');
    }

    public function getEmailChanges(): ActiveQuery
    {
        return $this->hasMany(UserEmailchangeEntity::class, ['user_id' => 'id'])
            ->andFilterWhere(['record_status' => self::RSTATUS_ACTIVE])
            ->orderBy('id DESC');
    }

    public static function tableName(): string
    {
        return '{{users}}';
    }
}