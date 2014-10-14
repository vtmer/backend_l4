<?php

class Tag extends Eloquent {

    /**
     * 属性：表名
     */
    protected $table = 'tags';

    /**
     * 标签下的文章
     * 多对多关系
     */
    public function articles()
    {
        return $this->belongsToMany('Article', 'article_tag',
            'tag_id', 'article_id');
    }
}
