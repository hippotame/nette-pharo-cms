<?php
namespace DB;

use Nette;

class BlogCategoryModel extends abstractDBModel
{

    protected $id;

    protected $name;

    protected $ordering;

    protected $table = 'blog_category';



    public function getCategories() {
        $sql = "
            SELECT
                a.*,
                t.context as cat
            FROM
                ".$this->table." a
            LEFT JOIN
                 ".$this->txt_table." t
                     ON ( t.id_blog_category=a.name AND t.lang=1 )
        ";
        $res = $this->db->query($sql)->fetchAll();
        $this->template->cats = $res;

    }




}