<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Examinstance
 *
 * @ORM\Table(name="ExamInstance", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="exam_instance_user_id_idx", columns={"user_id"}), @ORM\Index(name="exam_instance_exam_id_idx", columns={"exam_id"})})
 * @ORM\Entity
 */
class Examinstance
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
     * @var int|null
     *
     * @ORM\Column(name="grade", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $grade;

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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    private $answers;

    public function setAnswers($answers)
    {
        $this->answers = $answers;
        return $this;
    }

    public function answers()
    {
        return $this->answers;
    }

    public function setUserID(int $user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    public function getUserID()
    {
        return $this->user_id;
    }

    public function setExamID(int $exam_id)
    {
        $this->exam_id = $exam_id;
        return $this;
    }
    public function getExamID()
    {
        return $this->exam_id;
    }

    public function setGrade(?int $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): self
    {
        $this->exam = $exam;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
