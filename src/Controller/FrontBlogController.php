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
     *  @Route("/blog", name="blog")
     */
    public function index(BlogRepository $blogRepository) : Response
    {
        return $this->render('front/blog.html.twig', [
            'blogs' => $blogRepository->findAll(),
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show_id", methods={"GET"})
     */
    public function show(Blog $blog) : Response
    {
        return $this->render('frontblog-id.html.twig', [
            'blog' => $blog,
        ]);
    }
}