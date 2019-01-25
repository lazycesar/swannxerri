<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('front/home.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/formation", name="formation")
     */
    public function formation()
    {
        return $this->render('front/formation.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
