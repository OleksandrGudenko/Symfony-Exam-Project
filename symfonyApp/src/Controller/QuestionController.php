<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class QuestionController extends AbstractController
{
    public function questions($courseId)
    {
        $listData = $this->getDoctrine()->getRepository(Question::class)->findBy(['course' => $courseId]);
        $user = $this->getUser();

        return $this->render('questions/questions.html.twig',
            array('listData' => $listData,
                'courseId' => $courseId,
                'user' => $user));
    }

    public function addQuestion($courseId){
        $course = $this->getDoctrine()->getRepository(Course::class)->findOneBy(['id' => $courseId]);


        $newQuestion = new Question();
        $newQuestion->setCourse($course);
        $newQuestion->setQuestion('');
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($newQuestion);
        $manager->flush();

        $questionId = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['course' => $courseId], ['id' => 'DESC']);
        $questionId = $questionId->getId();
        return $this->redirectToRoute('viewQuestion', ['questionId' => $questionId]);
    }

    public function updateQuestion($questionId){
        $question = $this->getDoctrine()->getManager()->getRepository(Question::class)->find($questionId);
        $user = $this->getUser();
        return $this->render('questions/questionUpdated.html.twig',
                            ['question' => $question,
                                'user'=>$user]);
    }

    public function viewQuestion(Request $request, $questionId)
    {
        $question = $this->getDoctrine()->getManager()->getRepository(Question::class)->find($questionId);
        $user = $this->getUser();
        $form = $this->createFormBuilder($question)
            ->add('question', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save Changes'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            if($form->get('save')->isClicked())
            {
                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                $question = $form->getData();
                // ... perform some action, such as saving the task to the database
                // for example, if Task is a Doctrine entity, save it!
                $entityManager->persist($question);
                $entityManager->flush();
                return $this->redirectToRoute('updateQuestion', ['questionId' => $questionId]);
            }

        }
        return $this->render('questions/viewQuestion.html.twig', [
            'question' => $question,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}