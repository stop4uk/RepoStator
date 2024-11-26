<?php

namespace app\models\user;

use app\components\base\BaseModel;
use app\entities\user\UserEntity;
use app\helpers\{HtmlPurifier, RbacHelper, user\UserHelper,};
use Yii;
use yii\helpers\{ArrayHelper, Json};

/**
 * @property string $email
 * @property string $password
 * @property string $lastname
 * @property string $firstname
 * @property string|null $middlename
 * @property int|null $phone
 * @property int $account_status
 * @property int $account_cpass_required
 * @property int $group
 * @property array|null $rights;
 *
 * @property-read array $groups
 * @property-read array $allowRights
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\models\user
 */
final class UserModel extends BaseModel
{
    public $email;
    public $password;
    public $lastname;
    public $firstname;
    public $middlename;
    public $phone;
    public $account_status;
    public $account_cpass_required;
    public $group;
    public $rights;
    public $hasGroup;

    public readonly array $groups;
    public readonly array $allowRights;

    public function __construct(UserEntity $entity, array $config = [])
    {
        $this->account_status = UserEntity::STATUS_ACTIVE;
        $this->groups = RbacHelper::getAllowGroupsArray('admin.user.list.all');
        $this->allowRights = ArrayHelper::map(Yii::$app->getAuthManager()->getRoles(), 'name', 'description');

        parent::__construct($entity, $config);
    }

    public function init()
    {
        if ( !$this->isNewEntity ) {
            $this->password = null;

            if ( $this->entity->group !== null ) {
                $this->group = $this->entity->group->group_id ?? null;
                $this->hasGroup = $this->group;
            }

            if ( $this->entity->rights ) {
                foreach ( $this->entity->rights as $right) {
                    $this->rights[] = $right->item_name;
                }
            }
        }

        parent::init();
    }

    public function rules(): array
    {
        return [
            [['firstname', 'lastname', 'account_status'], 'required'],
            ['email', 'required', 'message' => Yii::t('models_error', 'Email обязателен')],
            ['email', 'string', 'length' => [4,58]],
            [
                'email',
                'unique', 'targetClass' => UserEntity::class,
                'filter' => $this->getUniqueFilterString(true),
                'message' => Yii::t('models_error', 'Данный email уже зарегистрирован в системе')
            ],

            ['password', 'string', 'length' => [4, 64]],
            [
                'password', 'required',
                'when' => fn($model) => $model->isNewEntity,
                'whenClient' => 'function(attribute, value) { return (value.length == 0 && ' . Json::encode($this->isNewEntity) . '); }'
            ],

            ['phone', 'string', 'length' => [10, 10]],

            ['lastname', 'string', 'length' => [2, 48]],
            [['firstname', 'middlename'], 'string', 'length' => [2, 24]],

            [['account_status', 'account_cpass_required', 'group', 'hasGroup'], 'integer'],
            ['account_status', 'in', 'range' => UserEntity::STATUSES],

            ['rights', 'each', 'rule' => ['in', 'range' => array_keys($this->allowRights)]],

            [['email', 'lastname', 'firstname', 'middlename', 'phone'], 'filter', 'filter' => fn ($value) => HtmlPurifier::process($value)],
        ];
    }

    public function attributeLabels(): array
    {
        return UserHelper::labels();
    }
}