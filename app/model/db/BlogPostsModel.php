<?php

 namespace App\DB;

 use Nette;

 class BlogPostsModel extends AbstractDBModel {

     public $id;
     public $date_created;
     public $date_to_show;
     public $post_perex;
     public $post_content;
     protected $table = 'blog_post';
     protected $id_txt = 'id_blog_post';

     public function countIn($id) {
         $this->catModel = new BlogCategoryModel($this->db);
         return $this->db->table($this->table)->where('id_blog_category', $id)->count();
     }

     public function loadFront($lang = 1, $start = 1, $limit = 10, $blog_category = null) {

         $now = new \Nette\Utils\DateTime();
         $this->getColumns();

         $joins = '';
         $selects = 'a.*';

         $a = 1;
         forEach ($this->txts as $key => $val) {

             $selects .= sprintf(",t%s.%s AS %s", $a, 'value', str_replace('_txt', '', $key));
             $selects .= sprintf(",t%s.date_updated AS t%s_updated", $a, $a);
             $selects .= sprintf(",t%s.id AS t%s_id", $a, $a);

             $joins .= " LEFT JOIN `" . $this->txt_table . "` t" . $a . " ";
             $joins .= " ON ( t" . $a . ".id=a." . $key . " AND t" . $a . ".lang=" . $lang . " )";

             $a++;
         }
         $selects .= ' ,cat.value as cat_name '; 
         $joins .= ' LEFT JOIN blog_category_txt cat ';
         $joins .= ' ON ( cat.id=a.id_blog_category AND cat.lang='.$lang.' ) '; 

         $sql = 'SELECT ' . $selects . ' FROM `' . $this->table . '` a ';

         $sql = $sql . $joins;

         $where = ' WHERE a.post_status=\'publish\' ';
         $where .= ' AND a.date_deleted IS NULL ';
         $where .= ' AND a.date_release < \'' . $now->format('Y-m-d H:i:s') . '\'  ';

         $order = ' ORDER BY a.date_release DESC ' ;

         $limit = ' LIMIT ' . $limit ;

         $sql = $sql . $where . $order . $limit; //echo $sql; die();

         return $this->db->query($sql)->fetchAll();
     }

 }

 /*$where = ' WHERE a.post_status=\'publish\' AND a.date_release < \'' . $now->format('Y-m-d H:i:s') . '\' ';
         $where .= 'AND a.date_closed > \'' . $now->format('Y-m-d H:i:s') . '\' ';
         $where .= ' AND a.date_deleted IS NULL ';

         $order = ' ORDER BY a.date_release DESC';

         $limit = ' LIMIT ' . $limit . ' OFFSET ' . $start . ' ';
         
         $sql = $sql . $where . $order . $limit;*/