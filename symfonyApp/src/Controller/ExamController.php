<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Exam;
use App\Entity\Examquestion;
use App\Entity\Answergiven;
use App\Entity\Examinstance;
use App\Entity\Question;
use App\Entity\User;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
                    'user'      => $user,
                    'courseId' => $courseId
            ));
    }

    public function createExam($courseId){

        $course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $courseId]);
        $user = $this->getUser();

        $newExam = new Exam();
        $newExam->setCourse($course);
        $newExam->setCreator($user);
        $newExam->setExamName('');
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($newExam);
        $manager->flush();

        $examId = $this->getDoctrine()->getRepository(Exam::class)->findOneBy(['course' => $courseId], ['id' => 'DESC']);
        $examId = $examId->getId();

        return $this->redirectToRoute('editExam', ['examId' => $examId]);

    }

    public function editExam(Request $request, $examId)
    {
        $exam = $this->getDoctrine()->getManager()->getRepository(Exam::class)->find($examId);
        $user = $this->getUser();
        $form = $this->createFormBuilder($exam)
            ->add('name', TextType::class, array('label' => 'Exam Name'))
            ->add('save', SubmitType::class, array('label' => 'Save Changes'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            if($form->get('save')->isClicked())
            {
                $exam = $form->getData();
                $entityManager->persist($exam);
                $entityManager->flush();
                return $this->redirectToRoute('updateExam', ['examId' => $examId]);
            }

        }
        return $this->render('exams/edit.html.twig', [
            'examId' => $examId,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    public function updateExam($examId){
        $exam = $this->getDoctrine()->getManager()->getRepository(Exam::class)->find($examId);
        $user = $this->getUser();
        $courseId = $exam->getCourse()->getId();

        return $this->render('exams/updateExam.html.twig',
            ['exam' => $exam,
             'user'=> $user,
             'courseId' => $courseId]);
    }

    public function questionsExam($examId)
    {
        $exam = $this->getDoctrine()->getManager()->getRepository(Exam::class)->find($examId);

        $questions = $this->getDoctrine()->getManager()->getRepository(Question::class)->findBy(['course' => $exam->getCourse()]);

        $examQuestions = $this->getDoctrine()->getRepository(Examquestion::class)->findBy(array('exam' => $exam));
        $exam->setQuestions($examQuestions);

        $myArray = array();

        foreach($examQuestions as $question)
        {
            array_push($myArray, $question->getQuestion());
        }

        $user = $this->getUser();

        return $this->render('exams/questions.html.twig',
            ['user'=> $user,
             'questions' => $questions,
             'examQuestions' => $myArray,
             'exam' => $exam   ]);
    }

    public function addQuestionToExam($examId, $questionId)
    {
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($examId);
        $question = $this->getDoctrine()->getRepository(Question::class)->find($questionId);

        $examQuestion = new Examquestion();

        $examQuestion->setExam($exam);
        $examQuestion->setQuestion($question);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($examQuestion);
        $manager->flush();

        return new Response();
    }

    public function students($examId)
    {
        $user = $this->getUser();

        $students = $this->getDoctrine()->getRepository(User::class)->findBy(['teacher' => 0]);

        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($examId);

        foreach ($students as $student)
        {
            $instances = $this->getDoctrine()->getRepository(Examinstance::class)->findBy(array('exam'=>$exam, 'user'=>$student));

            $student->setInstances($instances);
        }

        return $this->render('exams/students.html.twig',
            array(  'students'  => $students,
                    'exam'      => $examId,
                    'user'      => $user,
                    )   );
    }


    public function publishAll(Request $request)
    {
        $data = $request->getContent();
        $publishData = json_decode($data, true);

        $examKey='examId';
        $examId = $publishData[$examKey];
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($examId);

        $studentKey='studentIds';
        $students = $publishData[$studentKey];

        foreach($students as $studentId){

        $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);
        $instance = new Examinstance();
        $instance->setUser($student);
        $instance->setExam($exam);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($instance);
        $manager->flush();

        }

        return new Response();

    }

    public function publishExam($examId, $studentId)
    {
        $student = $this->getDoctrine()->getRepository(User::class)->find($studentId);
        $exam = $this->getDoctrine()->getRepository(Exam::class)->find($examId);

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

        $instanceKey='instance';
        $examInstance = $answerData[$instanceKey];

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


