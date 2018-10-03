<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Examinstance
 *
 * @ORM\Table(name="examinstance", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="exam_instance_user_id_idx", columns={"user_id"}), @ORM\Index(name="exam_instance_exam_id_idx", columns={"exam_id"})})
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


}
