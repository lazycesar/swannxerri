<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MemberAreaController extends AbstractController
{
    /**
     *  @Route("/membres", name="membre")
     */
    public function index(BlogRepository $blogRepository) : Response
    {
        return $this->render('front/membres.html.twig', [
            'membres' => $blogRepository->findAll(),
        ]);
    }
}