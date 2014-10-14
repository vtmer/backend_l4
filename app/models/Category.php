<?php

class Category extends Eloquent {

    /**
     * 属性：表名
     */
    protected $table = 'categories';

    /**
     * 分类下的文章
     * 多对多关系
     */
    public function articles()
    {
        return $this->belongsToMany('Article', 'article_category',
            'category_id', 'article_id');
    }
}
