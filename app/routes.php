<?php

Route::get('/', function()
{
	return View::make('hello');
});

/*
|--------------------------------------------------------------------
| 后台公开路由
|--------------------------------------------------------------------
*/
Route::get('/backend/login', array(
    'as' => 'BackendGetLogin',
    'uses' => 'Controllers\Backend\AdminController@getLogin'
));
Route::post('/backend/login', array(
    'as' => 'BackendPostLogin',
    'uses' => 'Controllers\Backend\AdminController@postLogin'
));

/*
|--------------------------------------------------------------------
| 后台管理
|--------------------------------------------------------------------
*/
Route::group(array('prefix' => 'backend', 'before'=> 'auth'), function () {

    #后台首页
    Route::get('/', array(
        'as' => 'BackendGetIndex',
        'uses' => 'Controllers\Backend\IndexController@getIndex'
    ));

    # 退出登录
    Route::get('/logout', array(
        'as' => 'BackendLogout',
        'uses' => 'Controllers\Backend\AdminController@getLogout'
    ));

    # 用户组管理
    Route::group(array('prefix' => 'users-group', 'before' => 'permission'), function() {

        #新建用户组
        Route::post('/create', array(
            'as' => 'BackendUserGroupCreate',
            'uses' => 'Controllers\Backend\GroupController@postCreateGroup'
        ));

        #修改用户组
        Route::post('/update/{id}', array(
            'as' => 'BackendUserGroupUpdate',
            'uses' => 'Controllers\Backend\GroupController@updateGroup'
        ));

        #删除用户组
        Route::post('/delete/{id}', array(
            'as' => 'BackendUserGroupDelete',
            'uses' => 'Controllers\Backend\GroupController@deleteGroup'
        ));

    });

    #用户管理
    Route::group(array('prefix' => 'users', 'before' => 'permission'), function () {

        # 用户管理首页
        Route::get('/', array(
            'as' => 'BackendUsersGetIndex',
            'uses' => 'Controllers\Backend\AdminController@getIndex'
        ));

        # 个人中心
        Route::get('/{id}', array(
            'as' => 'BackendUserPersonCenter',
            'uses' => 'Controllers\Backend\AdminController@getPersonCenter'
        ));

        # 修改个人信息
        Route::post('/edit/{id}', array(
            'as' => 'BackendUserEditDetail',
            'uses' => 'Controllers\Backend\AdminController@postDetail'
        ));

        #重置密码
        Route::post('reset-password', array(
            'as' => 'BackendUserResetPassword',
            'uses' => 'Controllers\Backend\AdminController@postPassword'
        ));

        # 注册用户
        Route::get('/signup', array(
            'as' => 'BackendUserGetSignup',
            'uses'=> 'Controllers\Backend\AdminController@getSignup'
        ));
        Route::post('/signup', array(
            'as' => 'BackendUserPostSignup',
            'uses' => 'Controllers\Backend\AdminController@postSignup'
        ));

        # 删除用户
        Route::get('/delete/{id}', array(
            'as' => 'BackendUserDelete',
            'uses' => 'Controllers\Backend\AdminController@postDelete'
        ));
    });

});

