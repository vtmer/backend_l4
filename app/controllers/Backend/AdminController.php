<?php namespace Controllers\Backend;

use Validator;
use Controllers\Backend\BackBaseController;
use User;
use Auth;
use Hash;

class AdminController extends BackBaseController {

    /**
     * 属性：用户ID
     * @var int
     */
    private $id;

    public function __construct()
    {
        if (Auth::check()) {
            $this->id = Auth::id();
        }
    }

    /**
     * 页面：用户管理首页
     * @return Response
     */
    public function getIndex()
    {
        return '这是用户管理首页';
    }

    /**
     * 页面：登录
     * @return Response
     */
    public function getLogin()
    {
        return "这是登录页面";
    }

    /**
     * 动作：登录
     * @return Response
     */
    public function postLogin()
    {
        // 凭证
        $credentials = array(
            'email' => Input::get('email'),
            'password' => Input::get('password')
        );
        // 是否记住登录状态
        $remember = Input::get('remember-me', false);
        if (Auth::attempt($credentials, $remember)) {
            return Redirect::intented();
        } else {
            return Redirect::back()
                ->withInput
                ->with('error', '用户名或密码不正确');
        }
    }

    /**
     * 动作：登出
     * @return Response
     */
    public function getLogout()
    {
        Auth::logout();

        return Redirect::to('/');
    }

    /**
     * 页面：注册
     * @return Response
     */
    public function getSignup()
    {
        return "这是注册页面";
    }

    /**
     * 动作：注册
     * @return Response
     */
    public function postSignup()
    {
        // 获取所有表单数据
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'email' => 'required|emial|unique:users',
            'password' => 'required|alpha_dash|between:6,16|confirmed',
        );
        // 自定义验证消息
        $messages = array(
            'email.required' => '请输入邮箱',
            'email.email' => '请输入正确的邮箱格式',
            'email.unique' => '此邮箱已被使用',
            'password.required' => '请输入密码',
            'password.alpha_dash' => '密码格式不正确',
            'password.between' => '密码长度保持在:min和:max之间',
            'password.confirmed' => '两次输入的密码不正确'
        );

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // 验证成功，添加用户
            $user = new User;
            $user->email = Input::get('email');
            $user->password = Input::get('password');
            $user->nickname = Input::get('nickname', 'None');
            if ($user->save()) {
                return Redirect::back();
            } else {
                // 添加失败
                return Redirect::back()
                    ->withInput()
                    ->withErrors(array('add' => '注册失败'));
            }
        } else {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * 页面：个人中心
     * @return Response
     */
    public function getPersonCenter($id)
    {
        if ($id !== $this->id) {
            return App::abort(404);
        }

        $user = User::findOrFail($id);

        return "这是个人信息";
    }

    /**
     * 动作：修改个人信息
     * @return Response
     */
    public function postDetail($id)
    {
        if ($id !== $this->id) {
            return App::abort(404);
        }
        // 获取全部数据
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'nickname' => 'required|alpha_dash|between:6,16',
        );
        // 自定义错误信息
        $messages = array(
            'nickname.required' => '请输入昵称',
            'nickname.alpha_dash' => '昵称格式不正确',
            'nickname.between' => '昵称长度为:min和:max之间',
        );

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            $user = User::findOrFail($id);
            $user->nickname = Input::get('nickname');
            if ($user->save()) {
                return Redirect::back()
                    ->withInput
                    ->withErrors(array('success' => '修改个人信息成功'));
            } else {
                return Redirect::back()
                    ->withInput()
                    ->with(array('error' => '修改失败'));
            }
        } else {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * 动作：修改密码
     * @return Response
     */
    public function postPassword($id)
    {
        if ($id !== $this->id) {
            return App::abort(404);
        }
        $user = User::findOrFail($id);

        // 获取所有表单数据
        $input = Input::only('originpassword', 'password');
        extract($input);

        // 创建验证规则
        $rules = array(
            'password' => 'required|alpha_dash|between:6,16|confirmed',
        );
        // 自定义验证消息
        $messages = array(
            'password.required' => '请输入密码',
            'password.alpha_dash' => '密码格式不正确',
            'password.between' => '密码长度保持在:min和:max之间',
            'password.confirmed' => '两次输入的密码不正确'
        );
        // 开始验证
        $validator = Validator::make($input, $rules, $messages);
        if ($validator->passes()) {
            // 验证原密码
            if (!Hash::check($originpassword, $user->password)) {
                return Redirect::back()
                    ->withInput
                    ->with('error', '原密码错误');
            }

            $user->password = Hash::make($password);
            if ($user->save()) {
                return Redirect::back()
                    ->with('success', '修改密码成功');
            } else {
                return Redirect::back()
                    ->withInput()
                    ->with('error', '修改失败');
            }
        } else {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * 动作：删除用户
     * @return Response
     */
    public function postDeleteUser($id)
    {
        if ($id == $this->id) {
            return App::abort(404);
        }

        $user = User::findOrFail($id);

        if ($user->delete()) {
            return Redirect::back()
                ->with('success', '成功删除用户');
        } else {
            return Redirect::back()
                ->with('error', '删除用户失败');
        }
    }

}
