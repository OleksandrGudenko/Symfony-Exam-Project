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
        $user = $this->getUser();

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
                'user' => $user,
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

        $instance = new Examinstance();
        $instance->setUser($student);
        $instance->setExam($exam);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($instance);
        $manager->flush();

        return new Response();
    }

    public function takeExam($instanceId)
    {
        $user = $this->getUser();

        $instance = $this->getInstance($instanceId);

        return $this->render('exams/take.html.twig',
            array(  'instance'  => $instance,
                    'user'      => $user)
        );
    }

    public function examResults($examId)
    {
        $user = $this->getUser();

        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($examId);
        $instances = $this->getDoctrine()->getRepository(Examinstance::class)->findBy(['exam'=>$exam]);

        return $this->render('exams/studentsResults.html.twig',
            array(  'instances' => $instances,
                    'user'      => $user
            )
        );
    }

    public function result($instanceId)
    {
        $user = $this->getUser();
        $instance = $this->getInstance($instanceId);
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

    public function getInstance($instanceId)
    {
        $instance = $this->getDoctrine()->getRepository(Examinstance::class)->find($instanceId);

        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($instance->getExam());

        $examQuestions = $this->getDoctrine()->getRepository(Examquestion::class)->findBy(array('exam'=>$exam));

        $exam->setQuestions($examQuestions);

        foreach($examQuestions as $examQuestion)
        {
            $question = $this->getDoctrine()->getRepository(Question::class)->find($examQuestion->getQuestion());
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('question'=>$question));

            $question->setAnswers($answers);

            $correctAnswer = $this->getDoctrine()->getRepository(Answer::class)->findOneBy(array('question'=>$question, 'correctAnswer'=>1));
            $question->setCorrectAnswer($correctAnswer);
        }

        $answersGiven = $this->getDoctrine()->getRepository(Answergiven::class)->findBy(array('examInstance'=>$instance));

        $instance->setAnswers($answersGiven);

        return $instance;
    }

    public function completeExam(Request $request)
    {
        $user = $this->getUser();
        $data = $request->getContent();
        $answerData = json_decode($data, true);

        $key='instance';
        $examInstance = $answerData[$key];

        $manager = $this->getDoctrine()->getManager();

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

                    $manager->persist($newAnswer);
                    $manager->flush();
                }
            }
        }

        $instance = $this->getInstance($examInstance);

        $grade = $this->calculateGrade($instance);
        $instance->setGrade($grade);

        $manager->persist($instance);
        $manager->flush();

        return $this->render('exams/complete.html.twig',

            array('data' => $answerData,
                'user' => $user));

    }

    public function calculateGrade($instance)
    {
        $answersGiven = $instance->answers();

        $correctAnswers = 0;

        foreach ($answersGiven as $answer)
        {
            if($answer->getAnswer()->getCorrectAnswer() == 1)
                $correctAnswers ++;
        }

        $result = ($correctAnswers / count($answersGiven) * 100);
        return $result;
    }
}


