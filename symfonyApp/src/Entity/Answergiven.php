<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answergiven
 *
 * @ORM\Table(name="answergiven", indexes={@ORM\Index(name="answer_given_answer_id_idx", columns={"answer_id"}), @ORM\Index(name="answer_exam_instance_id_idx", columns={"exam_instance_id"}), @ORM\Index(name="answer_given_exam_question_id_idx", columns={"question_id"})})
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


}
