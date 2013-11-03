<?php

namespace Questions\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="answer")
 */
class Answer
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $answer;

    /**
     * @var Question
     * @ORM\ManyToOne(targetEntity="Questions\Entity\Question", inversedBy="answers")
     */
    protected $question;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="Questions\Entity\Email", mappedBy="answer", cascade={"persist"})
     */
    protected $emails;

    /**
     * Initialies the answers variable.
     */
    public function __construct()
    {
        $this->emails = new ArrayCollection();
    }

    /**
     * Get the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Get the answer.
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set the answer.
     *
     * @param string $answer
     *
     * @return void
     */
    public function setAnswer($answer)
    {
        $this->answer = (string) $answer;
    }

    /**
     * Get the question
     *
     * @return \Questions\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set question.
     *
     * @param Question $question
     * @internal param \Questions\Entity\Question $question
     *
     * @return void
     */
    public function setQuestion(Question $question = null)
    {
        $this->question = $question;
    }


    /**
     * @param \Doctrine\Common\Collections\Collection $emails
     */
    public function addEmails($emails)
    {
        foreach ($emails as $email) {
            $email->setAnswer($this);
            $this->emails->add($email);
        }
    }

    /**
     * @param Email $email
     */
    public function addEmail(Email $email)
    {
        $email->setAnswer($this);
        $this->emails->add($email);
    }

    /**
     * @param Collection $emails
     */
    public function removeEmails(Collection $emails)
    {
        foreach ($emails as $email) {
            $email->setAnswer(null);
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
