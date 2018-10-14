<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Exam;
use App\Entity\Examquestion;
use App\Entity\Answergiven;
use App\Entity\Examinstance;
use App\Entity\Question;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $examDelete = $this->getDoctrine()->getRepository(Exam::class)->findOneBy(['id' => $examId]);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($examDelete);
        $entityManager->flush();
        return new Response();
    }

    public function publishExam($examId)
    {
        $students = $this->getDoctrine()->getRepository(User::class)->findBy(['teacher' => 0]);

        return $this->render('exams/students.html.twig',
            array('students' => $students)
        );
    }

    public function takeExam($examId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($examId);

        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(array('course' => $exam->getCourse_ID()));
        $exam->setQuestions($questions);

        foreach ($questions as $question) {
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('question' => $question->getID()));
            $question->setAnswers($answers);
        }

        $manager = $this->getDoctrine()->getManager();
        $instance = new Examinstance();
        $instance->setUser($this->getUser());
        $instance->setExam($exam);
        $manager->persist($instance);
        $manager->flush();
        return $this->render('exams/take.html.twig',
            array('instance' => $instance));
    }




    public function completeExam(Request $request)
    {
        $data = $request->getContent();
        $answerData = json_decode($data, true);

        $key='instance';
        $examInstance = $answerData[$key];

        foreach ($answerData as $result) {

            if (is_array($result) || is_object($result)) {

                foreach ($result as $question => $answer) {

                    $answer = $this->getDoctrine()->getRepository(Answer::class)->findOneBy(array('id' => $answer));
                    $question = $this->getDoctrine()->getRepository(Examquestion::class)->findOneBy(array('id' => $question));
                    $newAnswer = new Answergiven();
                    $newAnswer->setAnswer($answer);
                    $newAnswer->setQuestion($question);
                    $instance = $this->getDoctrine()->getRepository(Examinstance::class)->findOneby(array('id' => $examInstance));
                    $newAnswer->setExamInstance($instance);
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($newAnswer);
                    $manager->flush();
                }
            }

        }

        

        return $this->render('exams/complete.html.twig',
            array('data' => $answerData));

    }


    public function result($instanceId)
    {
        return $this->render('exams/result.html.twig');
    }


}


