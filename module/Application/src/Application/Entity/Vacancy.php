<?php
/**
 * Created by JetBrains PhpStorm.
 * User: danil
 * Date: 4/29/14
 * Time: 12:04 AM
 */

namespace Application\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */

class Vacancy
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="Division") */
    protected $division;

    /** @ORM\OneToMany(targetEntity="VacancyText", mappedBy="vacancy") */
    protected $vacancyTexts;

    public function __construct()
    {
        $this->vacancyTexts = new ArrayCollection();
    }

    /**
     * @param mixed $division
     */
    public function setDivision($division)
    {
        $this->division = $division;
    }

    /**
     * @return mixed
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $vacancyTexts
     */
    public function setVacancyTexts($vacancyTexts)
    {
        $this->vacancyTexts = $vacancyTexts;
    }

    /**
     * @return mixed
     */
    public function getVacancyTexts()
    {
        return $this->vacancyTexts;
    }

    /**
     * @param string $language a two-letter language-code
     *
     * @return VacancyText|null
     */
    public function getVacancyTextByLanguage( $language ) {
        //TODO: Replace with ArrayCollection::matching() call
        if ( empty( $this->vacancyTexts ) ) {
            return null;
        }

        foreach ( $this->vacancyTexts as $vt ) {
            /** VacancyText $vt */
            if ( $vt->getLanguage() == $language ) {
                return $vt;
            }
        }

        return null;
    }
}