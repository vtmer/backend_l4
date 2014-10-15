<?php namespace Controllers\Backend;

use Controllers\Backend\BackBaseController;
use Input;
use Article;
use Auth;
use Category;
use ArticleCategory;
use ArticleTag;

class ArticleController extends BackBaseController {

    /**
     * 属性：用户id
     * var int
     */
    protected $userId;


    public function __construct()
    {
        $userId = Auth::id();
    }

    /**
     * 页面：文章列表
     * @return Resonpse
     */
    public function getArticles()
    {
        return "这里是文章列表页面";
    }

    /**
     * 页面：回收站文章列表
     * @return Response
     */
    public function getTrashedArticles()
    {
        return "这里是回收站";
    }

    /**
     * 页面：文章编辑页面
     * @return Response
     */
    public function getUpdateArticles($id)
    {
        return "这里文章编辑页面";
    }


    /**
     * 动作：新增文章
     * @return Resonpse
     */
    public function createArticle()
    {
        $valiator = $this->getValiator();
        if ($valiator->passes()) {
            // 验证成功，添加文章
            extract(Input::all());
            // 文章概要
            $description = Input::get('description', null);
            $article = new Article;
            $article->title = $title;
            $article->content = $content;
            $article->description = $description;
            $article->author_id = $userId;
            $article->end_edit_author_id = $userId;
            $article->draft = $draft;
            if ($article->save()) {
                $this->saveArticleCategory($categories, $article->id);
                $this->saveTagCategory($tags, $article->id);
                return Redirect::back()
                    ->with('success', '文章保存成功');
            } else {
                return Redirect::back()
                    ->withInput()
                    ->with('error', '文章保存失败');
            }
        } else {
            return Redirect::back()
                ->withInput()
                ->withErrors($valiator);
        }
    }

    /**
     * 动作：修改文章
     * @return Resonpse
     */
    public function updateArticle($id)
    {
        $valiator = $this->getValiator();
        if ($valiator->passes()) {
            $article = Article::FindOrFail($id);
            extract(Input::all());
            $description = Input::get('description', null);
            $article->title = $title;
            $article->content = $content;
            $article->description = $description;
            $article->end_edit_author_id = $userId;
            $article->draft = $draft;

            if ($article->save()) {
                ArticleCategory::where('article_id', '=', $article->id)->delete();
                $this->saveArticleCategory($categories, $article->id);
                ArticleTag::where('article_id', '=', $article->id)->delete();
                $this->saveArticleTag($tags, $article->id);

                return Redirect::back()
                    ->with('success', '文章修改成功');
            } else {
                return Redirect::back()
                    ->with('error', '文章修改失败');
            }
        } else {
            return Redirect::back()
                ->withInput
                ->withErrors($valiator);
        }
    }

    /**
     * 动作：软删除文章,移至回收站
     * @return Resonpse
     */
    public function softdeleteArticle($id)
    {
        $article = Article::FindOrFail($id);
        if ($article->delete()) {
            return Redirect::back()
                ->with('success', '文章已移到回收站中');
        } else {
            return Redirect::back()
                ->with('error', '文章移到回收站失败');
        }
    }

    /**
     * 动作： 恢复文章
     * @return Response
     */
    public function restoreTrashedArticle($id)
    {
        $restoreArticle = Article::withTrashed()->where('id',$id)->get();
        if ($restoreArticle->restore()) {
            return Redirect::back()
                ->with('success', '文章恢复成功');
        } else {
            return Redirect::back()
                ->with('error', '文章恢复失败');
        }
    }

    /**
     * 动作：彻底删除文章
     * @return Resonpse
     */
    public function deleteTrashedArticle($id)
    {
        $trashedArticle = Article::withTrashed()->where('id',$id)->get();
        if ($this->deleteArticle($trashedArticle)) {
            return Redirect::back()
                ->with('success', '文章删除成功');
        } else {
            return Redirect::back()
                ->with('error', '文章删除错误');
        }
    }

    /**
     * 动作：清空回收站,删除所有被软删除的文章
     * @return Response
     */
    public function deleteAllTrashedArticle()
    {
        $trashedArticles = Article::onlyTrashed()->get();
        foreach ($trashedArticles as $trashedArticle) {
            if ($this->deleteArticle($trashedArticle)) {
                return Redirect::back()
                    ->with('success', '文章删除成功');
            } else {
                return Redirect::back()
                    ->with('error', '文章删除错误');
            }
        }
    }

    /**
     * 动作：改变文章排序
     * @return Response
     */
    public function articleSort()
    {
        // 获取数据，数组
        $datas = Input::get('articles', 0);
        if ($datas) {
            foreach ($datas as $data) {
                $article = Article::FindOrFail($data->id);
                $article->sort = $data->sort;
                $article->save();
            }

            return Redirect::back()
                ->with('success', '文章排序成功');
        } else {
            return Redirect::back()
                ->with('error', '请输入全部');
        }
    }

    /**
     * 动作：改变文章发布状态
     * @return Response
     */
    public function articlePublishStatus($id)
    {
        $article = Article::FindOrFail($id);
        if ($id !== 1) {
            $id = 0;
        }

        $article->draft = $id;
        if ($article->save()) {
            return Redirect::back()
                ->with('success', '文章发布状态改变成功');
        } else {
            return Redirect::back()
                ->with('error', '文章发布状态改变成功');
        }
    }

    /**
     * 方法：硬删除文章以及相关关系
     * @return boolean
     */
    public function deleteArticle($trashedArticle)
    {
        $trashedArticleId = $trashedArticle->id;
        if ($trashedArticleId->forceDelete()) {
            ArticleCategory::where('article_id', '=', $trashedArticleId)->delete();
            ArticleTag::where('article_id', '=', $trashedArticleId)->delete();

            return true;
        } else {
            return false;
        }
    }

    /**
     * 方法：验证Input
     * @return Valiator
     */
    public function getValiator()
    {
        // 获取数据
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'title' => 'required|between:2,128',
            'content' => 'required',
            'catagories' => 'required',
        );
        // 自定义验证消息
        $messages = array(
            'title.required' => '请输入标题',
            'title.between' => '请保持文章在:min 和:max之间',
            'content.required' => '请输入文章正文',
            'catagories.required' => '请至少选择一个分类',
        );
        $valiator = Valiator::make($data, $rules, $messages);

        return $valiator;
    }

    /**
     * 方法：保存文章分类关系
     * @return void
     */
    public function saveArticleCategory($categories, $articleId)
    {
        // 分类保存
        foreach ($categories as $category) {
            $articleCategory = new AricleCategory;
            $articleCategory->aricle_id = $article->id;
            $articleCategory->category_id = $category;
            $articleCategory->save();
        }

    }

    /**
     * 方法：保存文章标签关系
     * @return void
     */
    public function saveArticleTag($tags, $articleId)
    {
        // 标签
        foreach ($tags as $tag) {
            $tagCurrent = Tag::hasTag($tag);
            if (!$tagCurrent) {
            // 新建标签
                $tagCreate = new Tag;
                $tagCreate->tag = $tag;
                $tagCreate->save();
                $tagCurrent = $tagCreate;
            }
            // 保存文章标签关系
            $articleTag = new ArticleTag;
            $articleTag->article_id = $article->id;
            $articleTag->tag_id = $tagCurrent->id;
        }
    }
}
