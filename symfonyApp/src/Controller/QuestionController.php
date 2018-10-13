<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class QuestionController extends AbstractController
{
    public function questions($courseId)
    {
        $listData = $this->getDoctrine()->getRepository(Question::class)->findBy(['course' => $courseId]);

        return $this->render('questions/questions.html.twig',
            array('listData' => $listData));
    }

    public function editQuestion(Request $request, $questionId)
    {
        $questionData = $this->getDoctrine()->getRepository(Question::class)->find($questionId);

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

        return $this->render('questions/edit.html.twig',
            array('questionData' => $questionData,
                'editForm' => $form->createView()));
    }
}