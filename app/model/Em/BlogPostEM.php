<?php
namespace EM;

use Doctrine\ORM\EntityManager as ORM;

/**
 * @Entity @Table(name="blog_post_test")
 */
/**
 * @ORM\Entity
 */
class BlogPost
{

    public $id;

    public $id_blog_category;

    public $date_created;

    public $date_release;

    public $date_deleted;

    public $post_perex;

    public $post_content;

    public $post_header;

    public $post_title;

    public $post_image;

    public $post_can_comment;

    public $post_status;

    public $menu_order;
}