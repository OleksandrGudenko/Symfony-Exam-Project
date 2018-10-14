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
        $user = $this->getUser();

        $listData = null;

        if($user->getTeacher())
        {
            $listData = $this->getDoctrine()->getRepository(Exam::class)->findBy(['course' => $courseId]);
        } else {

            $exam = $this->getDoctrine()->getRepository(Exam::class)->findBy(['course'=>$courseId]);

            $listData = $this->getDoctrine()->getRepository(Examinstance::class)->findBy(['user' => $user, 'exam' => $exam]);
        }

        return $this->render('exams/exams.html.twig',
            array(  'listData'  => $listData,
                    'user'      => $user));
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

    public function students($examId)
    {
        $user = $this->getUser();

        $students = $this->getDoctrine()->getRepository(User::class)->findBy(['teacher' => 0]);

        return $this->render('exams/students.html.twig',
            array(  'students'  => $students,
                    'exam'      => $examId,
                    'user'      => $user)
        );
    }

    public function publishExam($examId, $studentId)
    {
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($examId);


        $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(array('course' => $exam->getCourse_ID()));
        $exam->setQuestions($questions);

        foreach ($questions as $question) {
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('question' => $question->getID()));
            $question->setAnswers($answers);
        }
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();

        $instance = new Examinstance();
        $instance->setUser($student);
        $instance->setExam($exam);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($instance);
        $manager->flush();


        return $this->render('exams/take.html.twig',
            array('instance' => $instance,
                'user' => $user));

    }

    public function takeExam($instanceId)
    {
        $user = $this->getUser();

        $instance = $this->getDoctrine()->getRepository(Examinstance::class)->find($instanceId);

        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($instance->getExam());

        $examQuestions = $this->getDoctrine()->getRepository(Examquestion::class)->findBy(array('exam'=>$exam));

        $exam->setQuestions($examQuestions);

        foreach($examQuestions as $examQuestion)
        {
            $question = $this->getDoctrine()->getRepository(Question::class)->find($examQuestion->getQuestion());
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('question'=>$question));

            $question->setAnswers($answers);
        }

        return $this->render('exams/take.html.twig',
            array(  'instance'  => $instance,
                    'user'      => $user)
        );
    }

    public function completeExam(Request $request)
    {
        $user = $this->getUser();
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

            array('data' => $answerData,
                'user' => $user));

    }

    public function result($instanceId)
    {
        $user = $this->getUser();
        $instance = $this->getDoctrine()->getRepository(ExamInstance::class)->findOneBy(array('id' => $instanceId));
        $answerGiven = $this->getDoctrine()->getRepository(AnswerGiven::class)->findBy(array('examInstance' => $instance));
        $answers = [];

        foreach($answerGiven as $correct) {
            array_push($answers, ($this->getDoctrine()->getRepository(Answer::class)->findBy(array('correctAnswer' => $correct))));
        }


        return $this->render('exams/result.html.twig',
            array('user' => $user,
                  'instance' => $instance,
                  ));

    }
}


