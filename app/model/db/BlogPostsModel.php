<?php
namespace App\DB;

use Nette;

class BlogPostsModel extends AbstractDBModel
{

    public $id;

    public $date_created;

    public $date_to_show;

    public $post_perex;

    public $post_content;

    protected $table = 'blog_post';

    protected $id_txt = 'id_blog_post';
    
   
    
     public function countIn($id) {
         $this->catModel = new BlogCategoryModel($this->db);
         return $this->db->table($this->table)->where('id_blog_category',$id)->count();
     }
    
}