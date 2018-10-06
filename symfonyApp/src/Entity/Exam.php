<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exam
 *
 * @ORM\Table(name="exam", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="exam_course_id_idx", columns={"course_id"})})
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
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

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

    public function setCourse_ID(string $course_id)
    {
        $this->course_id = $course_id;
        return $this;
    }
    public function getCourse_ID()
    {
        return $this->course_id;
    }

    public function setCreator_ID(string $creator_id)
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
