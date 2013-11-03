<?php

namespace Questions\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="question")
 */
class Question
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $question;

    /**
     * @var \ExpUser\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="ExpUser\Entity\User", inversedBy="questions")
     */
    protected $user;


    /**
     * @var int
     */
    protected $state;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Questions\Entity\Answer", mappedBy="question", cascade={"persist"})
     */
    protected $answers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Questions\Entity\Email", mappedBy="question", cascade={"persist"})
     */
    protected $emails;

    /**
     * Initialies the answers variable.
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->emails = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }


    /**
     * @param Collection $answers
     */
    public function addAnswers(Collection $answers)
    {
        foreach ($answers as $answer) {
            $answer->setQuestion($this);
            $this->answers->add($answer);
        }
    }

    /**
     * @param Collection $answers
     */
    public function removeAnswers(Collection $answers)
    {
        foreach ($answers as $answer) {
            $answer->setQuestion(null);
            $this->answers->removeElement($answer);
        }
    }

    /**
     * @return Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * @param string $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param \ExpUser\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \ExpUser\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $emails
     */
    public function addEmails($emails)
    {
        foreach ($emails as $email) {
            $email->setQuestion($this);
            $this->emails->add($email);
        }
    }

    /**
     * @param Email $email
     */
    public function addEmail(Email $email)
    {
        $email->setQuestion($this);
        $this->emails->add($email);
    }

    /**
     * @param Collection $emails
     */
    public function removeEmails(Collection $emails)
    {
        foreach ($emails as $email) {
            $email->setQuestion(null);
            $this->email->removeElement($email);
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmails()
    {
        return $this->emails;
    }


}
