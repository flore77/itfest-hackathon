<?php

namespace SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="star_q")
 * @ORM\Entity(repositoryClass="SurveyBundle\Entity\StarRepository")
 */

class StarQ {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $tqid;

    /**
     * @ORM\ManyToOne(targetEntity="SurveyBundle\Entity\Survey", inversedBy="starQ")
     * @ORM\JoinColumn(name="survey_sid", referencedColumnName="sid")
     */
    protected $survey;

    /**
     * @ORM\Column(type="string")
     */
    protected $text;

    /**
     * Get tqid
     *
     * @return integer
     */
    public function getTqid()
    {
        return $this->tqid;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return StarQ
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

    public function __construct($survey) {
        $this->text = "";
        $this->survey = $survey;
    }

    /**
     * Set survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return StarQ
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
