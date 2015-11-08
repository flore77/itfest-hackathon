<?php

namespace SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="survey")
 * @ORM\Entity(repositoryClass="SurveyBundle\Entity\SurveyRepository")
 */

define('UNPUBLISHED', 0);
define('PUBLISHED', 1);
define('STEP1', 4);
define('STEP2', 2);
define('STEP3', 3);

class Survey {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $sid;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="surveys")
     * @ORM\JoinColumn(name="owner_uid", referencedColumnName="uid")
     */
    protected $owner;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\Column(type="integer")
     */
    protected $freeTextNo;

    /**
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\FreeText", mappedBy="survey")
     */
    protected $freeText;

    /**
     * @ORM\Column(type="integer")
     */
    protected $templateQNo;

    /**
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\TemplateQ", mappedBy="survey")
     */
    protected $templateQ;

    /**
     * @ORM\Column(type="integer")
     */
    protected $starQNo;

    /**
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\StarQ", mappedBy="survey")
     */
    protected $starQ;


    public function __construct() {
    }

    /**
     * Get sid
     *
     * @return integer
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * Set owner
     *
     * @param \UserBundle\Entity\User $owner
     *
     * @return Survey
     */
    public function setOwner(\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Survey
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function getFreeTextNo() {
        return $this->freeTextNo;
    }

    public function setFreeTextNo($nr) {
        $this->freeTextNo = $nr;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Survey
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add freeText
     *
     * @param \SurveyBundle\Entity\FreeText $freeText
     *
     * @return Survey
     */
    public function addFreeText(\SurveyBundle\Entity\FreeText $freeText)
    {
        $this->freeText[] = $freeText;

        return $this;
    }

    /**
     * Remove freeText
     *
     * @param \SurveyBundle\Entity\FreeText $freeText
     */
    public function removeFreeText(\SurveyBundle\Entity\FreeText $freeText)
    {
        $this->freeText->removeElement($freeText);
    }

    /**
     * Get freeText
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFreeText()
    {
        return $this->freeText;
    }

    /**
     * Set templateQNo
     *
     * @param integer $templateQNo
     *
     * @return Survey
     */
    public function setTemplateQNo($templateQNo)
    {
        $this->templateQNo = $templateQNo;

        return $this;
    }

    /**
     * Get templateQNo
     *
     * @return integer
     */
    public function getTemplateQNo()
    {
        return $this->templateQNo;
    }

    /**
     * Set starQNo
     *
     * @param integer $starQNo
     *
     * @return Survey
     */
    public function setStarQNo($starQNo)
    {
        $this->starQNo = $starQNo;

        return $this;
    }

    /**
     * Get starQNo
     *
     * @return integer
     */
    public function getStarQNo()
    {
        return $this->starQNo;
    }

    /**
     * Add templateQ
     *
     * @param \SurveyBundle\Entity\TemplateQ $templateQ
     *
     * @return Survey
     */
    public function addTemplateQ(\SurveyBundle\Entity\TemplateQ $templateQ)
    {
        $this->templateQ[] = $templateQ;

        return $this;
    }

    /**
     * Remove templateQ
     *
     * @param \SurveyBundle\Entity\TemplateQ $templateQ
     */
    public function removeTemplateQ(\SurveyBundle\Entity\TemplateQ $templateQ)
    {
        $this->templateQ->removeElement($templateQ);
    }

    /**
     * Get templateQ
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTemplateQ()
    {
        return $this->templateQ;
    }

    /**
     * Add starQ
     *
     * @param \SurveyBundle\Entity\StarQ $starQ
     *
     * @return Survey
     */
    public function addStarQ(\SurveyBundle\Entity\StarQ $starQ)
    {
        $this->starQ[] = $starQ;

        return $this;
    }

    /**
     * Remove starQ
     *
     * @param \SurveyBundle\Entity\StarQ $starQ
     */
    public function removeStarQ(\SurveyBundle\Entity\StarQ $starQ)
    {
        $this->starQ->removeElement($starQ);
    }

    /**
     * Get starQ
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStarQ()
    {
        return $this->starQ;
    }
}
