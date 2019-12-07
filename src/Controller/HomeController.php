<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        //Temp to avoid twig errors..
        $bookmarks = [];

        return $this->render('home/index.html.twig', [
            'bookmarks' => $bookmarks
        ]);
    }
}
