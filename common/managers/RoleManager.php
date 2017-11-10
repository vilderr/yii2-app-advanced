<?php

namespace common\managers;

use yii\rbac\ManagerInterface;

/**
 * Class RoleManager
 * @package common\managers
 */
class RoleManager
{
    private $_manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->_manager = $manager;
    }

    public function assign($userId, $roles)
    {
        $am = $this->_manager;
        $rm = [];
        foreach ($roles as $role) {
            if (!$r = $am->getRole($role)) {
                throw new \DomainException('Role "' . $role . '" does not exist.');
            }

            $rm[] = $r;
        }
        $am->revokeAll($userId);

        foreach ($rm as $m) {
            $am->assign($m, $userId);
        }
    }
}