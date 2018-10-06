<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exam
 *
 * @ORM\Table(name="Exam", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="exam_course_id_idx", columns={"course_id"}), @ORM\Index(name="exam_user_id_idx", columns={"creator_id"})})
 * @ORM\Entity
 */
class Exam
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var \Course
     *
     * @ORM\ManyToOne(targetEntity="Course")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
     */
    private $course;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     * })
     */
    private $creator;

    public function setCourse_ID(int $course_id)
    {
        $this->course_id = $course_id;
        return $this;
    }
    public function getCourse_ID()
    {
        return $this->course_id;
    }

    public function setCreator_ID(int $creator_id)
    {
        $this->creator_id = $creator_id;
        return $this;
    }
    public function getCreator_ID(){
        return $this->creator_id;
    }

    public function setExamName(string $name)
    {
        $this->name = $name;
        return $this;
    }
    public function getExamName(){
        return $this->name;
    }
}
