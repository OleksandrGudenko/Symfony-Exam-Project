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
use Symfony\Component\HttpFoundation\Response;

class ExamController extends AbstractController
{
    public function exams($courseId)
    {
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

        #$user = $this->getId();

        return $this->render('exams/examinstance.html.twig',
            array('exam' => $exam,
                'name' => $name,
                'answers' => $answers,
                'user' => $user));

    }



    public function completeExam(Request $request)
    {

        $data = $request->getContent();
        $answerData = json_decode($data, true);

        foreach( $answerData as $key => $value) {

            if(isset($value)){


                $answer = $this->getDoctrine()->getRepository(Answer::class)->findOneBy(array('id' => $value));
                $question = $this->getDoctrine()->getRepository(Examquestion::class)->findOneBy(array('id' => $key));
                $instance = $this->getDoctrine()->getRepository(Examinstance::class)->findOneby(array('id' => 1));

                $manager = $this->getDoctrine()->getManager();
                $newAnswer = new Answergiven();
                $newAnswer->setAnswer($answer);
                $newAnswer->setQuestion($question);
                $newAnswer->setExamInstance($instance);
                $manager->persist($newAnswer);
                $manager->flush();
            }

        }


        return $this->render('exams/complete.html.twig',
            array('data' => $answerData));

    }
}

