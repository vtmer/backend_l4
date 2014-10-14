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

    #栏目管理
    Route::group(array('prefix' => 'category', 'before' => 'permission'), function () {

        #新建栏目
        Route::post('/create', array(
            'as' => 'BackendCategoryCreate',
            'uses' => 'Controllers\Backend\CategoryControler@createCategory'
        ));

        #修改栏目
        Route::post('/update/{id}', array(
            'as' => 'BackendCategoryUpdate',
            'uses' => 'Controllers\Backend\CategoryControler@updateCategroy'
        ));

        #删除栏目
        Route::post('/delete/{id}', array(
            'as' => 'BackendCategoryDelete',
            'uses' => 'Controllers\Backend\CategoryControler@deleteCategoty'
        ));
    });

    # 文章管理
    Route::group(array('prefix' => 'articles', 'before' => 'permission'), function () {

        # 文章列表页面
        Route::get('/', array(
            'as' => 'BackendArticle',
            'uses' => 'Controllers\Backend\ArticleController@getArticles'
        ));

        # 回收站页面
        Route::get('/trashed', array(
            'as' => 'BackendTrashed',
            'uses' => 'Controllers\Backend\ArticleController@getTrashedArticles'
        ));

        # 文章发表
        Route::post('/create', array(
            'as' => 'BackendArticleCreate',
            'uses' => 'Controllers\Backend\ArticleController@createArticle'
        ));

        # 文章编辑页面
        Route::get('/update/{id}', array(
            'as' => 'BackendArticleGetUpdate',
            'uses' => 'Controllers\Backend\ArticleController@getUpdateAricles'
        ));

        # 文章修改
        Route::post('/update/{id}', array(
            'as' => 'BackendArticleUpdate',
            'uses' => 'Controllers\Backend\ArticleController@updateArticle'
        ));

        # 文章彻底删除
        Route::get('/delete/{id}', array(
            'as' => 'BackendArticleDelete',
            'uses' => 'Controllers\Backend\ArticleController@deleteTrashedArticle'
        ));

        # 清空回收站
        Route::get('/empty-trash', array(
            'as' => 'BackendArticleTrashEmpty',
            'uses' => 'Controllers\Backend\ArticleController@deleteAllTrashedArticle'
        ));

        # 文章软删除
        Route::get('/trashed/{id}', array(
            'as' => 'BackendArticleTrash',
            'uses' => 'Controllers\Backend\ArticleController@softdeleteArticle'
        ));

        # 文章恢复
        Route::get('/restore/{id}', array(
            'as' => 'BackendArticleRestore',
            'uses' => 'Controllers\Backend\ArticleController@restoreTrashedArticle'
        ));

        # 改变文章排序
        Route::post('/sort', array(
            'as' => 'BackendArticleSort',
            'uses' => 'Controllers\Backend\ArticleController@articleSort'
        ));

        # 改变文章发布状态
        Route::get('/status/{id}', array(
            'as' => 'BackendArticleStatus',
            'uses' => 'Controllers\Backend\ArticleController@articlePublicStatus'
        ));

    });

});

