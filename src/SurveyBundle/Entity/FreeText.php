<?php

namespace SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="free_text")
 * @ORM\Entity(repositoryClass="SurveyBundle\Entity\FreeTextRepository")
 */

class FreeText {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $ftid;

    /**
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\Survey", inversedBy="freeText")
     * @ORM\JoinColumn(name="survey_sid", referencedColumnName="sid")
     */
    protected $survey;

    /**
     * @ORM\Column(type="string")
     */
    protected $text;

    public function __construct($survey) {
        $this->text = "";
        $this->survey = $survey;
    }

    /**
     * Get ftid
     *
     * @return integer
     */
    public function getFtid()
    {
        return $this->ftid;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return FreeText
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return FreeText
     */
    public function setSurvey(\SurveyBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return \SurveyBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }
}
