<?php

use app\modules\users\components\rbac\rules\{
    CheckMainRule,
    CheckAllRule,
    CheckDeleteAllRule,
    CheckDeleteGroupRule,
    CheckDeleteMainRule,
    CheckDataAllRule,
    CheckGroupRule,
    CheckUserAllRule,
    CheckUserGroupRule,
    CheckUserDeleteAllRule,
    CheckUserDeleteGroupRule
};

return [
    'checkMain' => serialize(new CheckMainRule()),
    'checkAll' => serialize(new CheckAllRule()),
    'checkDeleteAll' => serialize(new CheckDeleteAllRule()),
    'checkDeleteGroup' => serialize(new CheckDeleteGroupRule()),
    'checkDeleteMain' => serialize(new CheckDeleteMainRule()),
    'checkDataAll' => serialize(new CheckDataAllRule()),
    'checkGroup' => serialize(new CheckGroupRule()),
    'checkUserAll' => serialize(new CheckUserAllRule()),
    'checkUserGroup' => serialize(new CheckUserGroupRule()),
    'checkUserDeleteAll' => serialize(new CheckUserDeleteAllRule()),
    'checkUserDeleteGroup' => serialize(new CheckUserDeleteGroupRule()),
];
