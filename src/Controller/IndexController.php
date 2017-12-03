<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\User;

class IndexController extends Controller
{
    public function index()
    {
        $number = mt_rand(0, 100);

        return $this->render('base.html.twig', array(
            'number' => $number,
        ));
    }

    public function register(Request $request)
    {
        // create a task and give it some dummy data for this example
        $task = new User();
        $task->setUsername('someguy');
        $task->setPassword('password4567!');

        $form = $this->createFormBuilder($task)
            ->add('username', TextType::class)
            ->add('password', TextType::class)
            ->add('confirm', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Account'))
            ->getForm();

        return $this->render('pages/forms/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function login()
    {
        $number = mt_rand(0, 100);

        return $this->render('pages/forms/login.html.twig', array(
            'number' => $number,
        ));
    }
}