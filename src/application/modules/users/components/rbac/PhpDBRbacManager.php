<?php

namespace app\modules\users\components\rbac;

use Yii;
use yii\db\{
    Connection,
    Query
};
use yii\di\Instance;
use yii\rbac\{
    Assignment,
    DbManager,
    PhpManager,
    Item,
};

use app\modules\users\components\rbac\items\Roles;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\components\rbac
 *
 * @see DbManager
 * @see PhpManager
 */
final class PhpDBRbacManager extends PhpManager
{
    public $itemFile = '@app/modules/users/components/rbac/data/item_child.php';
    public $ruleFile = '@app/modules/users/components/rbac/data/rule.php';

    public string $adminRole = Roles::ADMIN;
    public string $assignmentTable = '{{%users_rights}}';
    public array $checkAccessAssignments = [];
    public Connection|array|string $db = 'db';

    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);

        $this->itemFile = Yii::getAlias($this->itemFile);
        $this->ruleFile = Yii::getAlias($this->ruleFile);
        $this->load();
    }

    public function checkAccess($userId, $permissionName, $params = []): bool
    {
        if ( isset($this->checkAccessAssignments[(string) $userId]) ) {
            $assignments = $this->checkAccessAssignments[(string) $userId];
        } else {
            $assignments = $this->getAssignments($userId);
            $this->checkAccessAssignments[(string) $userId] = $assignments;
        }

        if ( $this->hasNoAssignments($assignments) ) {
            return false;
        }

        if ( in_array($this->adminRole, array_keys($assignments)) ) {
            return true;
        }

        return $this->checkAccessRecursive($userId, $permissionName, $params, $assignments);
    }

    public function getAssignment($roleName, $userId): ?Assignment
    {
        if ( $this->isEmptyUserId($userId) ) {
            return null;
        }

        $row = (new Query())->from($this->assignmentTable)
            ->where(['user_id' => (string) $userId, 'item_name' => $roleName])
            ->one($this->db);

        if ( $row === false ) {
            return null;
        }

        return new Assignment([
            'userId' => $row['user_id'],
            'roleName' => $row['item_name'],
            'createdAt' => $row['created_at'],
        ]);
    }

    public function getAssignments($userId): array
    {
        if ( $this->isEmptyUserId($userId) ) {
            return [];
        }

        $query = (new Query())
            ->from($this->assignmentTable)
            ->where(['user_id' => (string) $userId]);

        $assignments = [];
        foreach ($query->all($this->db) as $row) {
            $assignments[$row['item_name']] = new Assignment([
                'userId' => $row['user_id'],
                'roleName' => $row['item_name'],
                'createdAt' => $row['created_at'],
            ]);
        }

        return $assignments;
    }

    public function assign($role, $userId): Assignment
    {
        $assignment = new Assignment([
            'userId' => $userId,
            'roleName' => $role->name,
            'createdAt' => time(),
        ]);

        $this->db->createCommand()
            ->insert($this->assignmentTable, [
                'user_id' => $assignment->userId,
                'item_name' => $assignment->roleName,
                'created_at' => $assignment->createdAt,
            ])->execute();

        unset($this->checkAccessAssignments[(string) $userId]);

        return $assignment;
    }

    public function revoke($role, $userId): bool
    {
        if ( $this->isEmptyUserId($userId) ) {
            return false;
        }

        unset($this->checkAccessAssignments[(string) $userId]);
        $result = $this->db->createCommand()
                ->delete($this->assignmentTable, ['user_id' => (string) $userId, 'item_name' => $role->name])
                ->execute() > 0;

        return $result;
    }

    public function revokeAll($userId): bool
    {
        if ( $this->isEmptyUserId($userId) ) {
            return false;
        }

        unset($this->checkAccessAssignments[(string) $userId]);
        $result = $this->db->createCommand()
                ->delete($this->assignmentTable, ['user_id' => (string) $userId])
                ->execute() > 0;

        return $result;
    }

    public function removeAllAssignments(): void
    {
        $this->checkAccessAssignments = [];
        $this->db->createCommand()->delete($this->assignmentTable)->execute();
    }

    public function getUserIdsByRole($roleName): array
    {
        if ( empty($roleName) ) {
            return [];
        }

        return (new Query())->select('[[user_id]]')
            ->from($this->assignmentTable)
            ->where(['item_name' => $roleName])->column($this->db);
    }

    public function getRolesByUser($userId): array
    {
        $roles = $this->getDefaultRoleInstances();
        foreach ($this->getAssignments($userId) as $name => $assignment) {
            $role = $this->items[$assignment->roleName] ?? null;
            if (
                $role
                && $role->type === Item::TYPE_ROLE
            ) {
                $roles[$name] = $role;
            }
        }

        return $roles;
    }

    protected function removeAllItems($type): void
    {
        $names = [];
        foreach ($this->items as $name => $item) {
            if ($item->type == $type) {
                unset($this->items[$name]);
                $names[$name] = true;
            }
        }

        if ( empty($names) ) {
            return;
        }

        $this->db->createCommand()
            ->delete($this->assignmentTable, ['item_name' => $names])
            ->execute();

        foreach ($this->children as $name => $children) {
            if (isset($names[$name])) {
                unset($this->children[$name]);
            } else {
                foreach ($children as $childName => $item) {
                    if (isset($names[$childName])) {
                        unset($children[$childName]);
                    }
                }
                $this->children[$name] = $children;
            }
        }

        $this->saveItems();
    }

    protected function isEmptyUserId($userId): bool
    {
        return !isset($userId) || $userId === '';
    }
}
