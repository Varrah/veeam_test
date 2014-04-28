<?php
/**
 * Created by JetBrains PhpStorm.
 * User: danil
 * Date: 4/29/14
 * Time: 12:04 AM
 */

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */

class Division
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $title;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
}