<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Exam;
use App\Entity\Examinstance;
use App\Entity\Examquestion;
use App\Entity\Question;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Response;

class ExamController extends AbstractController
{
    public function exams($courseId)
    {
        $listData = $this->getDoctrine()->getRepository(Exam::class)->findBy(['course' => $courseId]);
        $user = $this->getUser();
        //$listData = $this->getDoctrine()->getRepository(Exam::class)->findBy(array('course'=>$courseId, 'creator'=>$user->getId()));

        return $this->render('exams/exams.html.twig',
            array('listData' => $listData,
                'user' => $user));
    }

    public function editExam(Request $request, $examId)
    {
        $examData = $this->getDoctrine()->getRepository(Exam::class)->find($examId);

        $form = $this->createFormBuilder($examData)
            //->add('save', SubmitType::class, array('label' => 'Update course'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $examData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($examData);
            $entityManager->flush();
        }

        return $this->render('exams/edit.html.twig',
            array('examData' => $examData,
                'editForm' => $form->createView()));
    }

    public function deleteExam($examId)
    {
        return new Response();
    }

    public function students($examId)
    {
        $students = $this->getDoctrine()->getRepository(User::class)->findBy(['teacher' => 0]);

        return $this->render('exams/students.html.twig',
            array(  'students'  => $students,
                    'exam'      => $examId)
        );
    }

    public function publishExam($examId, $studentId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($examId);

        $examQuestions = $this->getDoctrine()->getRepository(Examquestion::class)->findBy(array('exam'=>$exam->getId()));
        $exam->setQuestions($examQuestions);

        foreach($examQuestions as $examQuestion)
        {
            $question = $this->getDoctrine()->getRepository(Question::class)->find($examQuestion->getQuestion());

            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('question'=>$question->getId()));
            $question->setAnswers($answers);
        }

        $manager = $this->getDoctrine()->getManager();

        $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);

        $instance = new Examinstance();
        $instance->setUser($student);
        $instance->setExam($exam);
        $manager->persist($instance);
        $manager->flush();

        return new Response();
    }

    public function takeExam($instanceId)
    {
        $instance = $this->getDoctrine()->getRepository(Examinstance::class)->find($instanceId);

        return $this->render('exams/take.html.twig',
            array('instance' => $instance));
    }

    public function complete($request)
    {
        $data = $request->request->all();

        return $this->render('exams/examcompleted.html.twig',
            array('data' => $data));
    }
}

