<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table(name="Answer", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="answer_question_id_idx", columns={"question_id"})})
 * @ORM\Entity
 */
class Answer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=255, nullable=false)
     */
    private $answer;

    /**
     * @var bool
     *
     * @ORM\Column(name="correct_answer", type="boolean", nullable=false)
     */
    private $correctAnswer;

    /**
     * @var \Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * })
     */
    private $question;

    public function setAnswer(string $answer)
    {
        $this->answer = $answer;

        return $this;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setCorrectAnswer(bool $correctAnswer)
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }

    public function getCorrectAnswer()
    {
        return $this->correctAnswer;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }



}
