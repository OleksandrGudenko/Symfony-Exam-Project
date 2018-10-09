<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answergiven
 *
 * @ORM\Table(name="AnswerGiven", indexes={@ORM\Index(name="answer_given_answer_id_idx", columns={"answer_id"}), @ORM\Index(name="answer_exam_instance_id_idx", columns={"exam_instance_id"}), @ORM\Index(name="answer_given_exam_question_id_idx", columns={"question_id"})})
 * @ORM\Entity
 */
class Answergiven
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Examinstance
     *
     * @ORM\ManyToOne(targetEntity="Examinstance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_instance_id", referencedColumnName="id")
     * })
     */
    private $examInstance;

    /**
     * @var \Answer
     *
     * @ORM\ManyToOne(targetEntity="Answer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     * })
     */
    private $answer;

    /**
     * @var \Examquestion
     *
     * @ORM\ManyToOne(targetEntity="Examquestion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * })
     */
    private $question;

    public function setAnswerID(int $answer_id)
    {
        $this->answer_id = $answer_id;
        return $this;
    }
    public function getAnswerID(){
        return $this->answer_id;
    }

    public function setQuestionId(int $question_id)
    {
        $this->question_id = $question_id;
        return $this;
    }
    public function getQuestionId(){
        return $this->question_id;
    }

    public function setExamInstanceID(int $exam_instance_id)
    {
        $this->exam_instance_id = $exam_instance_id;
        return $this;
    }
    public function getExamInstanceID(){
        return $this->exam_instance_id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExamInstance(): ?Examinstance
    {
        return $this->examInstance;
    }

    public function setExamInstance(?Examinstance $examInstance): self
    {
        $this->examInstance = $examInstance;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getQuestion(): ?Examquestion
    {
        return $this->question;
    }

    public function setQuestion(?Examquestion $question): self
    {
        $this->question = $question;

        return $this;
    }
}
