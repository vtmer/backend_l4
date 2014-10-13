<?php namespace Controllers\Backend;

use Controllers\Backend\BackBaseController;
use Validator;
use Auth;
use Group;
use Action;
use Redirect;
use Input;

class GroupController extends BackendBaseController {

    /**
     * 动作：新建用户组
     * @return Response
     */
    public function postCreateGroup()
    {
        // 获取数据
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'name' => 'required|between:2,20',
            'actions' => 'required'
        );
        // 自定义验证消息
        $messages = array(
            'name.required' => '请输入用户组名',
            'name.between' => '组名长度保持在:min和:max之间',
            'actions.required' => '至少选择一个权限'
        );
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // 验证成功,新建组名
            $group = new Group;
            $group->name = Input::get('name');


            if ($group->save()) {
                $groupId = $group->id;
                // 添加权限
                $actions = Input::get('actions');
                foreach ($actions as $action) {
                    $group_actions = new Action;
                    $group_actions->code = $action;
                    $group_actions->group_id = $groupId;
                    if (!$group_actions->save()) {
                        return Redirect::back()
                            ->with('error', '权限分配失败');
                    }
                }

                return Redirect::back()
                    ->with('success', '成功新建用户组');
            } else {
                return Redirect::back()
                    ->with('error', '添加失败');
            }
        } else {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * 动作：修改用户组
     * @return Response
     */
    public function updateGroup($id)
    {
        # 获取数据
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'name' => 'required|between:2,20',
            'actions' => 'required'
        );
        // 自定义验证消息
        $messages = array(
            'name.required' => '请输入用户组名',
            'name.between' => '组名长度保持在:min和:max之间',
            'actions.required' => '至少选择一个权限'
        );

        // 开始验证
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            $group = Group::findOrFail($id);
            $group->name = Input::get('name');
            if ($group->save()) {
                # 删除用户组权限
                Action::where('group_id', '=', $group->id)->delete();
                $actions = Input::get('actions');
                foreach ($actions as $action) {
                    $group_actions = new Action();
                    $group_actions->code = $action;
                    $group_actions->group_id = $group->id;
                }

                return Redirect::back()
                    ->with('success', '修改成功');
            } else {
                return Redirect::back()
                    ->with('error', '修改失败');
            }
        } else {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * 动作：删除用户组
     * @return Response
     */
    public function deleteGroup($id)
    {
        $group = Group::findOrFail($id);
        if ($group->delete()) {
            Action::where('group_id', '=', $group->id)->delete();

            return Redirect::back()
                ->with('success', '删除成功');
        }
    }
}
