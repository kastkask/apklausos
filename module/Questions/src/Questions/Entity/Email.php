<?php

namespace Questions\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="email")
 */
class Email
{
    const STATE_NEW = 0;
    const STATE_SENT = 1;
    const STATE_VIEWED = 2;
    const STATE_ANSWERED = 3;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $email;

    /**
     * @ORM\ManyToOne(targetEntity="Questions\Entity\Answer", inversedBy="emails")
     */
    protected $answer;

    /**
     * @var Question
     * @ORM\ManyToOne(targetEntity="Questions\Entity\Question", inversedBy="emails")
     */
    protected $question;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $state;

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
        $this->answer = $answer;
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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


}
