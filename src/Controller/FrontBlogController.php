<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontBlogController extends AbstractController
{
    /**
     *  @Route("/frontblog.html", name="blog")
     */
    public function index(BlogRepository $blogRepository) : Response
    {
        return $this->render('frontblog.html.twig', [
            'blogs' => $blogRepository->findAll(),
        ]);
    }
}