<?php

class Article extends Eloquent {

    use SoftDeletingTrait;

    /**
     * 属性：表名
     */
    protected $table = 'categories';

    /**
     * 文章对应的标签
     * 多对多关系
     */
    public function articles()
    {
        return $this->belongsToMany('Tag', 'article_tag',
            'article_id', 'tag_id');
    }
}
