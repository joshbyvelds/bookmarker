<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function index()
    {
        $number = mt_rand(0, 100);

        return $this->render('base.html.twig', array(
            'number' => $number,
        ));
    }

    public function register()
    {
        $number = mt_rand(0, 100);

        return $this->render('pages/forms/register.html.twig', array(
            'number' => $number,
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