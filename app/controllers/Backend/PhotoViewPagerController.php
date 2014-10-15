<?php namespace Controllers\Backend;

use Controllers\Backend\BackBaseController;
use Auth;
use Image;
use Input;
use Valiator;

class PhotoViewPagerController extends BackBaseController {

    /**
     * 页面：图片轮播
     * @return Response
     */
    public function getPhotoViewPager()
    {
        return "这是图片轮播";
    }

    /**
     * 动作：新建图片轮播
     * @return Response
     */
    public function createPhotoViewPager()
    {
        // 获取数据
        $data = Input::all();
        // 创建规则
        $rules = array(
            'code' => 'required',
        );
        // 自定义消息
        $messages = array(
            'code.required' => '请至少选择一个图片轮播位置',
        );
        $valiator = Valiator::make($data, $rules, $messages);
        if ($valiator->passes()) {
            // 新建图片轮播
            $photoViewPager = new PhotoViewPager;
            $photoViewPager->name = Input::get('name');
            $photoViewPager->code = Input::get('code');
            if ($photoViewPager) {
                if ($photoViewPager->save()) {
                    return Redirect::back()
                        ->with('success', '新建图片轮播成功');
                } else {
                    return Redirect::back()
                        ->with('error', '新建图片轮播成功');
                }
            }
        } else {
            return Redirect::back()
                ->withErrors($valiator);
        }
    }

    /**
     * 动作：上传图片
     * @return Response
     */
    public function uploadImage($id)
    {
        $phtotViewPager = PhotoViewPager::FindOrFail($id);
        // 获取数据
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'image' => 'required|mimes:jepg,gif,png|max:1024',
        );
        // 自定义验证消息
        $messages = array(
            'image.required' => '请选择需要上传的图片',
            'image.mimes' => '请上传:values 格式的图片',
            'image.max' => '图片的大小请控制在1M之内',
        );
        $valiator = Valiator::make($data, $rules, $messages);
        if ($valiator->passes()) {
            $image = Input::file('image');
            $ext = $image->guessClientExtension(); // 根据mime类型取得真实拓展名
            $fullname = $image->getClientOriginalName(); // 客户端文件名，包括客户端拓展名
            $hashname = date('H.i.s').'-'.md5($fullname).'.'.$ext;
            // 图片信息入库
            $photo = Image::make($image->getRealPath());
            $photo->save(public_path('photoviewpager/'.$hashame));
            // 图片路径保存进数据库
            $photovpImage = new PhotoViewPagerImage;
            $photovpImage->photoviewPager_id = $photoViewPager->id;
            $photovpImage->image_path = $hashname;
            if ($photovpImage->save()) {
                return Redirect::back()
                    ->with('success', '图片成功');
            } else {
                return Redirect::back()
                    ->with('error', '图片上传失败');
            }
        } else {
            return Redirect::back()
                ->withErrors($valiator);
        }
    }

    /**
     * 动作：删除图片
     * @return Response
     */
    public function deleteImage($id)
    {
        $photoViewPagerImage = PhotoViewPagerImage::FindOrFail($id);
        // 删除库存图片
        File::delete(
            public_path('photoviewpager/'.$photoViewPagerImage->image_path)
        );
        if ($photoViewPagerImage->dlete()) {
            return Redirect::back()
                ->with('success', '图片删除成功');
        } else {
            return Redirect::back()
                ->with('error', '图片删除失败');
        }
    }

    /**
     * 动作：删除图片轮播
     * @return Response
     */
    public function deletePhotoViewPager($id)
    {
        $phtotViewPager = PhotoViewPager::FindOrFail($id);
        $photoViewPagerId = $photoViewPager->id;
        if ($photoViewPager->delete()) {
            // 删除图片轮播与图片关系
            PhotoViewPagerImage::where('photoviewpager_id', '=', $photoViewPagerId)->delete();
            return Redirect::back()
                ->with('success', '删除成功');
        } else {
            return Redirect::back()
                ->with('success', '删除失败');
        }
    }
}
