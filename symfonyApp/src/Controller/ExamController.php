<?php

namespace App\Controller;

use App\Entity\Answergiven;
use App\Entity\Exam;
use App\Entity\Course;
use App\Entity\Examinstance;
use App\Entity\Examquestion;
use App\Entity\Question;
use App\Entity\Answer;
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
        $id = $user->getId();
        $listData = $this->getDoctrine()->getRepository(Exam::class)->findBy(array('course'=>$courseId, 'creator'=>$id));

        return $this->render('exams/exams.html.twig',
            array('listData' => $listData,
                'id' => $id,
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

    public function takeExam(Request $request, $examId)
    {

        $exam = $this->getDoctrine()->getRepository(Examquestion::class)->findBy(array('exam'=>$examId));
        $examForInstance = $this->getDoctrine()->getRepository(Examquestion::class)->findOneby(array('exam'=>$examId));
        $name = $this->getDoctrine()->getRepository(Exam::class)->findOneby(array('id'=>$examId));
        $answers = $this->getDoctrine()->getRepository(Answer::class)->findAll();
        $name =  $name->getExamName();
        $examForInstanceExam = $examForInstance->getExam();
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $newInstance = new Examinstance();
        $newInstance->setUser($user);
        $newInstance->setExam($examForInstanceExam);
        $newInstance->setGrade('0');
        $manager->persist($newInstance);
        $manager->flush();

        $answerGiven = new Answergiven();
        $form = $this->createFormBuilder($answerGiven)
            ->add('question', HiddenType::class)
            ->add('answer', TextType::class)
            ->add('examInstance', HiddenType::class)
            ->add('save', SubmitType::class, array('label' => 'Form Submit'))
            ->getForm();

        if($form)
        {
            #$form->get('examInstance')->setData($newInstance);
            $answerGivenData= $form->getData();
            $form->get('question')->setData($examForInstance->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answerGivenData);
            $entityManager->flush();
            # $this->redirectToRoute('take' );
        }

        return $this->render('exams/examinstance.html.twig',
            array('exam' => $exam,
                'name' => $name,
                'answers' => $answers,
                'form' => $form->createView(),));
    }

    public function complete($request)
    {
        $data = $request->request->all();

        return $this->render('exams/examcompleted.html.twig',
            array('data' => $data));
    }
}

