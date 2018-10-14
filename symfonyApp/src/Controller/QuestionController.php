<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\NewQuestion;

class QuestionController extends AbstractController
{
    public function questions($courseId)
    {
        $listData = $this->getDoctrine()->getRepository(Question::class)->findBy(['course' => $courseId]);
        $user = $this->getUser();

        return $this->render('questions/questions.html.twig',
            array('listData' => $listData,
                'user' => $user));
    }

    public function editQuestion(Request $request, $questionId)
    {
        $questionData = $this->getDoctrine()->getRepository(Question::class)->find($questionId);
        $user = $this->getUser();

        $form = $this->createFormBuilder($questionData)
            //->add('save', SubmitType::class, array('label' => 'Update course'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $questionData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($questionData);
            $entityManager->flush();
        }

        $newQuestion = new Question();
        $newForm = $this->createForm(NewQuestion::class, $newQuestion, array('action' => $this->generateUrl('questionAddNew')));
        $newForm->add('save', SubmitType::class);

        return $this->render('questions/edit.html.twig',
            array('questionData' => $questionData,
                'editForm' => $form->createView(),
                'user' => $user,
                'newQuestionForm' =>  $newForm->createView()));
    }

    public function addQuestion(){
        $user = $this->getUser();

        $newQuestion = new Question();
        $newForm = $this->createForm(NewQuestion::class, $newQuestion, array('action' => $this->generateUrl('questionAddNew')));
        $newForm->add('save', SubmitType::class);


        return $this->render('questions/edit.html.twig',
            array('user' => $user,
                'newQuestionForm' =>  $newForm->createView()));
    }

    public function questionAddNew(Request $request)
    {
        $newQuestion = new Question();
        $newForm = $this->createForm(NewQuestion::class, $newQuestion);
        $newForm->handleRequest($request);
        if ($newForm->isSubmitted() && $newForm->isValid())
        {
            $newQuestion = $newForm->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newQuestion);
            $entityManager->flush();
            return $this->redirectToRoute('students');
        }
        else
        {
            return $this->redirectToRoute('courses');
        }
    }

    public function updateQuestion($id){
        $question = $this->getDoctrine()->getManager()->getRepository(Question::class)->find($id);
        return $this->render('course', ['question' => $question]);
    }
}