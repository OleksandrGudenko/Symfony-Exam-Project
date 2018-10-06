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

    /**
     * @var bool
     *
     * @ORM}Column(name="correct_answer", type="boolean", nullable=false)
     */
    private $correct_answer;

    public function setQuestion_ID(string $question_id)
    {
        $this->question_id = $question_id;
        return $this;
    }
    public function getQuestion_ID(){
        return $this->question_id;
    }

    public function setAnswer(string $answer)
    {
        $this->answer = $answer;
        return $this;
    }
    public function getAnswer(){
        return $this->answer;
    }

    public function setCorrectAnswer(string $correct_answer)
    {
        $this->correct_answer = $correct_answer;
        return $this;
    }
    public function getCorrectAnswer(){
        return $this->correct_answer;
    }

}
