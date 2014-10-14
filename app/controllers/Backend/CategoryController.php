<?php namespace Controllers\Backend;

use Controllers\Backend\BackBaseController;
use Input;
use Validator;
use Category;
use Article;
use ArticleCategory;


class CategoryController extends BackBaseController {

    /**
     * 动作：新建栏目
     * @return Response
     */
    public function createCategory()
    {
        // 获取数据
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'category' => 'required|between:2,30',
        );
        // 自定义验证消息
        $messages = array(
            'category.required' => '请输入栏目名称',
            'category.between' => '栏目名称保持在:min和:max之间',
        );
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // 验证成功，新建栏目
            $category = new Category;
            $category->category = Input::get('category');
            $category->top_category = Input::get('top_category', 1);
            $category->parent_category = Input::get('parent_category', 0);
            if ($category->save()) {
                return Redirect::back()
                    ->with('success', '新建栏目成功');
            } else {
                return Redirect::back()
                    ->with('error', '栏目新建失败');
            }
        } else {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * 动作：修改栏目
     * @return Response
     */
    public function updateCategory($id)
    {
        $data = Input::all();
        $rules = array('category' => 'required|between:2,30');
        $messages = array(
            'category.required' => '请输入栏目名称',
            'category.between' => '栏目名称保持在:min和:max之间',
        );
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            $category = Category::findOrFail($id);
            $category->category = Input::get('category');
            if ($category->save()) {
                return Redirect::back()
                    ->with('success', '栏目修改成功');
            } else {
                return Redirect::back()
                    ->with('error', '栏目修改失败');
            }
        } else {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * 动作：删除栏目
     * @return Response
     */
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $categoryId= $category->id;
        $sub_category = Category::where('parent_category', '=', $categoryId);

        // 删除栏目下的文章
        $articles = $category->articles();
        foreach ($articles as $article) {
            $article->delete();
        }
        // 删除关联关系
        ArticleCategory::where('category_id', '=', $categoryId)->delete();
        // 删除栏目
        if ($category->delete() && $sub_category->delete()) {
            return Redirect::back()
                ->with('success', '栏目删除成功');
        } else {
            return Redirect::back()
                ->with('error', '栏目删除失败');
        }
    }
}
