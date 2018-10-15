<?php

namespace App\Controller;

use App\Entity\Examinstance;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class StudentController extends AbstractController
{
    public function students(Request $request, $examId)
    {
        $students = $this->getDoctrine()->getRepository(User::class)->findBy(['teacher' => 0]);
        $user = $this->getUser();


        return $this->render('students/students.html.twig',
            array('students' => $students,
                'user' => $user));
    }
}