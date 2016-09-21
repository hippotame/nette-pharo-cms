<?php
namespace CommonDB\Test;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TestModel
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;
}