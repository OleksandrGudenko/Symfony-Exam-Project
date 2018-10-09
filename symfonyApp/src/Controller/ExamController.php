<?php

namespace App\Controller;

use App\Entity\Exam;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ExamController extends AbstractController
{
    public function exams()
    {
        $listData = $this->getDoctrine()->getRepository(Exam::class)->findAll();

        return $this->render('exams/exams.html.twig',
            array('listData' => $listData ));
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
}