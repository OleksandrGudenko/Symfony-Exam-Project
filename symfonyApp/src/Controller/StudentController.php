<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class StudentController extends AbstractController
{
    public function students(Request $request)
    {
        $listData = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('students/students.html.twig',
            array('students' => $listData ));
    }
}