<?php namespace Controllers\Backend;

use Controllers\Backend\BackBaseController;
use Tag;
use Article;
use ArticleTag;


class TagController extends BackBaseController {

    /**
     * 动作：删除标签
     * @return Response
     */
    public function deletetag($id)
    {
        $tag = tag::findOrFail($id);
        $tagId= $tag->id;

        // 删除关联关系
        ArticleTag::where('tag_id', '=', $tagId)->delete();
        // 删除标签
        if ($tag->delete()) {
            return Redirect::back()
                ->with('success', '标签删除成功');
        } else {
            return Redirect::back()
                ->with('error', '标签删除失败');
        }
    }
}
