<?php

class Group extends Eloquent {

    /**
     * 属性：表名
     */
    protected $table = 'groups';

    /**
     * 用户组的权限
     * 一对多
     */
    public function actions()
    {
        return $this->hasmany('Action', 'group_id');
    }

    /**
     * 检查是否拥权限
     * @return boolean
     */
    public function hasPermission($perm)
    {
        foreach ($this->actions as $action) {
            // action['code'] 对应app/config/permission 权限code
            if ($action->code === $perm) {
                return true;
            }
        }
        return false;
    }

}
