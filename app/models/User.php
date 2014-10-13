<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    /**
     * 用户组
     * 一对一:逆向
     */
    public function group()
    {
        return $this->belongsTo('Group', 'group_id');
    }

    /**
     * 用户所属用户组的权限
     * @return boolean
     */
    public function hasPermission($perm)
    {
        if ($this->group->hasPermission($perm)) {
            return true;
        }
        return false;
    }

}
