<?php
/**
 * Created by PhpStorm.
 * User: Jamie
 * Date: 03/10/2018
 * Time: 15:09
 */
namespace App\Controller;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;

class TeacherController extends AbstractController
{
    public function users(Request $request)
    {
        $listData = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('users/list.html.twig',
            array('listData' => $listData ));
    }
}