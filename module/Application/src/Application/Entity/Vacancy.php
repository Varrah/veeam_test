<?php
/**
 * Created by JetBrains PhpStorm.
 * User: danil
 * Date: 4/29/14
 * Time: 12:04 AM
 */

namespace Application\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
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
     * Returns vacancy texts as Doctrine\Common\Collections\ArrayCollection
     * if language is provided returns only texts in selected language
     * if text in selected language was not found - returns text in English
     * if text in English is not found - returns empty collection
     *
     * @param string $language language code, as stored in DB/search form
     *
     * @return ArrayCollection vacancyTexts
     */
    public function getVacancyTexts($language = null)
    {
        if (empty($language)) {
            return $this->vacancyTexts;
        } else {
            $texts = $this->vacancyTexts->matching(
                Criteria::create()->where(Criteria::expr()->eq('language', $language))
            );
            if ($texts->count() == 0) {
                return $this->vacancyTexts->matching(Criteria::create()->where(Criteria::expr()->eq('language', 'en')));
            }
            return $texts;
        }
    }
}