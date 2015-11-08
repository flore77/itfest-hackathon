<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\UserRepository")
 */

class User extends BaseUser {

    /**
     * @ORM\Column(type="datetime")
     */
    protected $joinDate;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    protected $email;

    /**
     * @ORM\OneToMany(targetEntity="SurveyBundle\Entity\Survey", mappedBy="owner")
     */
    protected $surveys;

    // Auxiliary variables used for validations.
    protected $verifyPassword;

    /**
     * Constructor
     *
     * Set the joining date of the user
     */
    public function __construct() {
        date_default_timezone_set('Europe/Bucharest');

        $this->joinDate = new \DateTime(date("d-m-Y H:i:s"));
        $this->roles = array("ROLE_USER");
    }

    /**
     * used for validation
     * @return bool
     */
    public function isPasswordLegal() {
        return $this->password != $this->verifyPassword;
    }

    /**
     * Set joinDate
     *
     * @param \DateTime $joinDate
     *
     * @return User
     */
    public function setJoinDate($joinDate)
    {
        $this->joinDate = $joinDate;

        return $this;
    }

    /**
     * Get joinDate
     *
     * @return \DateTime
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $verifyPassword
     * @return $this
     */
    public function setVerifyPassword($verifyPassword) {
        $this->verifyPassword = $verifyPassword;

        return $this;
    }

    /**
     * Get verify password
     *
     * @return string
     */
    public function getVerifyPassword() {
        return $this->verifyPassword;
    }

    /**
     * Add survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return User
     */
    public function addSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->surveys[] = $survey;

        return $this;
    }

    /**
     * Remove survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     */
    public function removeSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->surveys->removeElement($survey);
    }

    /**
     * Get surveys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveys()
    {
        return $this->surveys;
    }
}
