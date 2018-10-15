<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Answer;
use SendGrid\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnswerController extends AbstractController
{

    public function answers($questionId){

        $listData = $this->getDoctrine()->getRepository(Answer::class)->findBy(['question' => $questionId]);
        $user = $this->getUser();

        return $this->render('answers/answers.html.twig',
            array('listData' => $listData,
                'questionId' => $questionId,
                'user' => $user));
    }

    public function addAnswer($questionId){
        $question = $this->getDoctrine()->getRepository(Question::class)->findOneBy(['id' => $questionId]);


        $newAnswer = new Answer();

        $newAnswer->setQuestion($question);
        $newAnswer->setAnswer('');
        $newAnswer->setCorrectAnswer('');
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($newAnswer);
        $manager->flush();


        $answerId = $this->getDoctrine()->getRepository(Answer::class)->findOneBy(['question' => $questionId], ['id' => 'DESC']);
        $answerId = $answerId->getId();
        return $this->redirectToRoute('viewAnswer', ['answerId' => $answerId]);
    }

    public function updateAnswer($answerId){
        $answer = $this->getDoctrine()->getManager()->getRepository(Answer::class)->find($answerId);
        $user = $this->getUser();
        return $this->render('answers/answerUpdated.html.twig',
            ['answer' => $answer,
                'user'=>$user]);
    }

    public function viewAnswer(Request $request, $answerId)
    {
        $answer = $this->getDoctrine()->getManager()->getRepository(Answer::class)->find($answerId);

        $question = $answer->getQuestion();
      //  $questionId = $question->getId();
        $questionAnswers = $this->getDoctrine()->getManager()->getRepository(Answer::class)->findBy(['question' =>$question]);

        $correctAnswer = null;

        foreach($questionAnswers as $questionAnswer)
        {
            if($questionAnswer->getCorrectAnswer() == 1)
            {
                $correctAnswer = $questionAnswer;
                break;
            }
        }

        $user = $this->getUser();
        $form = $this->createFormBuilder($answer)
            ->add('answer', TextType::class)
            ->add('correctAnswer', ChoiceType::class, array(
                'choices' => array('Yes' => true, 'No' => false)))
            ->add('save', SubmitType::class, array('label' => 'Save Changes'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            if($form->get('save')->isClicked())
            {
                $formAnswer = $form->getData();
                $formAnswer->getCorrectAnswer();

                if($correctAnswer == null || $formAnswer->getCorrectAnswer() == 0 ){

                    $answer = $form->getData();

                    $entityManager->persist($answer);
                    $entityManager->flush();
                    return $this->redirectToRoute('updateAnswer', ['answerId' => $answerId]);
                }
                else {

                    //if you edit to yes

                    //if you edit to no

                    $answer = $form->getData();
                    $answer->setCorrectAnswer(false);
                    $entityManager->persist($answer);
                    $entityManager->flush();
                    return $this->render('answers/correctAnswerExist.html.twig', [
                        'answer' => $answer,
                        'answerId' => $answerId,
                        'user' => $user
                    ]);
                }
            }
        }

        return $this->render('answers/viewAnswer.html.twig', [
                'question' => $answer,
                'user' => $user,
                'form' => $form->createView()
        ]);



    }


}