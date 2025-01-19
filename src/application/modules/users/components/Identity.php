<?php

namespace stop4uk\users\components;

use Yii;
use yii\web\IdentityInterface;

use stop4uk\users\{
    entities\UserEntity,
    entities\GroupNestedEntity,
    repositories\GroupRepository,
    helpers\UserHelper
};

/**
 * @property-read int|null $group
 * @property-read array $groups
 * @property-read array $groupsParent
 * @property-read string $fullName
 * @property-read string $shortName
 * @property-read bool $needChangePassword
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\bootstrap
 */
final class Identity implements IdentityInterface
{
    public readonly int|null $group;
    public readonly array $groups;
    public readonly array $groupsParent;
    public readonly string $fullName;
    public readonly string $shortName;
    public readonly bool $needChangePassword;

    private UserEntity $user;

    public function __construct(UserEntity $user)
    {
        $this->user = $user;
        $this->getGroups();
        $this->getNames();
        $this->getReadValues();
    }

    public static function findIdentity($id): ?self
    {
        $user = UserEntity::find()
            ->where([
                'id' => $id,
                'account_status' => UserEntity::STATUS_ACTIVE
            ])
            ->with(['group'])
            ->limit(1)
            ->one();

        return $user ? new self($user): null;
    }

    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        $user = UserEntity::find()
            ->where([
                'account_key' => $token,
                'account_status' => UserEntity::STATUS_ACTIVE
            ])
            ->with(['group'])
            ->limit(1)
            ->one();

        return $user ? new self($user): null;
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getAuthKey(): string
    {
        return $this->user->account_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    private function getGroups(): void
    {
        $this->group = $this->user->group->group_id ?? null;

        if ($this->group) {
            $allGroups = GroupRepository::getAll([], true);
            if (Yii::$app->getUser()->can('admin')) {
                foreach (array_keys($allGroups) as $groupID) {
                    $this->groups[$groupID] = $groupID;
                }

                return;
            }

            $nestedRecord = GroupNestedEntity::find()->where(['group_id' => $this->group])->limit(1)->one();

            $groups = [$this->group => $allGroups[$this->group]];
            $groupsParent = [];

            $children = $nestedRecord->children(100)->all();
            $parents = $nestedRecord->parents(100)->all();

            if ($children) {
                foreach ($children as $child) {
                    $groups[$child->group_id] = $allGroups[$child->group_id];
                }
            }

            if ($parents) {
                foreach ($parents as $parent) {
                    $groupsParent[$parent->group_id] = $parent->group_id;
                }
            }

            ksort($groups);
            ksort($groupsParent);
        }

        $this->groups = $groups ?? [];
        $this->groupsParent = $groupsParent ?? [];
    }

    private function getNames(): void
    {
        $arrayFields = $this->user->toArray(['lastname', 'firstname', 'middlename']);

        $this->fullName = UserHelper::getFullName($arrayFields);
        $this->shortName = UserHelper::getShortName($arrayFields);
    }

    private function getReadValues(): void
    {
        $this->needChangePassword = (bool)$this->user->account_cpass_required;
    }
}
