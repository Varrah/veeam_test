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

class VacancyText
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Vacancy", inversedBy="vacancyTexts")
     */
    protected $vacancy;

    /** @ORM\Column(type="string", length=2) */
    protected $language;

    /** @ORM\Column(type="text") */
    protected $titleText;

    /** @ORM\Column(type="text") */
    protected $descriptionText;

    /**
     * @param mixed $descriptionText
     */
    public function setDescriptionText($descriptionText)
    {
        $this->descriptionText = $descriptionText;
    }

    /**
     * @return mixed
     */
    public function getDescriptionText()
    {
        return $this->descriptionText;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $titleText
     */
    public function setTitleText($titleText)
    {
        $this->titleText = $titleText;
    }

    /**
     * @return mixed
     */
    public function getTitleText()
    {
        return $this->titleText;
    }

    /**
     * @param mixed $vacancy
     */
    public function setVacancy($vacancy)
    {
        $this->vacancy = $vacancy;
    }

    /**
     * @return mixed
     */
    public function getVacancy()
    {
        return $this->vacancy;
    }

}