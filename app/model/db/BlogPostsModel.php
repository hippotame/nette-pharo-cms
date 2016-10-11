<?php
namespace DB;

use Nette;

class BlogPostsModel extends abstractDBModel
{

    public $id;

    public $date_created;

    public $date_to_show;

    public $post_perex;

    public $post_content;

    protected $table = 'blog_post';

    protected $id_txt = 'id_blog_post';
    
   
    
}