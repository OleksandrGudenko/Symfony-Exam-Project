<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Examquestion
 *
 * @ORM\Table(name="ExamQuestion", indexes={@ORM\Index(name="exam_question_exam_id_idx", columns={"exam_id"}), @ORM\Index(name="exam_question_question_id_idx", columns={"question_id"})})
 * @ORM\Entity
 */
class Examquestion
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
     * @var \Exam
     *
     * @ORM\ManyToOne(targetEntity="Exam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_id", referencedColumnName="id")
     * })
     */
    private $exam;

    /**
     * @var \Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * })
     */
    private $question;

        public function setQuestionID(int $Questionid)
    {
        $this->question_id = $Questionid;
        return $this;
    }
    public function getQuestionID(){
        return $this->question_id;
    }

    public function setExamID(int $Examid)
    {
        $this->exam_id = $Examid;
        return $this;
    }
    public function getexamID(){
        return $this->exam_id;
    }
}
