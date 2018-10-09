<?php

namespace App\Controller;

use App\Entity\Course;
use SendGrid\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CourseController extends AbstractController
{
    public function courses(Request $request)
    {
        $listData = $this->getDoctrine()->getRepository(Course::class)->findAll();

        return $this->render('courses/courses.html.twig',
            array('listData' => $listData ));
    }

    public function editCourse(Request $request, $courseId)
    {
        $courseData = $this->getDoctrine()->getRepository(Course::class)->find($courseId);

        $form = $this->createFormBuilder($courseData)
            //->add('save', SubmitType::class, array('label' => 'Update course'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $courseData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($courseData);
            $entityManager->flush();
        }

        return $this->render('courses/edit.html.twig',
            array('courseData' => $courseData,
                'editForm' => $form->createView()));
    }

    public function deleteCourse($courseId)
    {
        return new Response();
    }
}