<?php namespace Controllers\Backend;

use Controllers\Backend\BackBaseController;
use Input;
use Validator;

class Video extends BackBaseController {

    /**
     * 页面：显示视频
     * @return Response
     */
    public function getVideos()
    {
        return "这里是视频页面";
    }

    /**
     * 动作：新增一条视频
     * @return Response
     */
    public function createVideo()
    {
        // 获取数据
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'name' => 'required',
            'link' => 'required',
            'code' => 'required'
        );
        // 自定义验证消息
        $messages = array(
            'name.required' => '请选择视频位置',
            'link.required' => '请输入视频链接',
            'code.required' => '请输入视频位置代号'
        );
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            $video = new Video;
            $video->name = Input::get('name');
            $video->linke = Input::get('link');
            $video->code = Input::get('code');
            if ($video->save()) {
                return Redirect::back()
                    ->with('success', '成功创建视频');
            } else {
                return Redirect::back()
                    ->with('error', '视频创建失败');
            }
        } else {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * 动作：修改一条视频
     * @return
     */
    public function updateVideo($id)
    {
        $data = Input::all();
        $rules = array('link' => 'required');
        $messages = array('link.required' => '请输入链接');

        $video = Video::FindOrFail($id);
        $video->link = Input::get('link');
        if ($video->save()) {
            return Redirect::back()
                ->with('success', '视频修改成功');
        } else {
            return Redirect::back()
                ->with('error', '视频修改失败');
        }
    }

    /**
     * 动作：删除一条视频
     * @return
     */
    public function deleteVideo($id)
    {
        $video = Video::FindOrFail($id);
        if ($video->delete()) {
            return Redirect::back()
                ->with('success', '成功删除一条视频');
        } else {
            return Redirect::back()
                ->with('error', '视频删除失败');
        }
    }
}
