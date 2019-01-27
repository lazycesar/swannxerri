<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VideoRepository;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(VideoRepository $videoRepository)
    {
        return $this->render('front/home.html.twig', [
            'controller_name' => 'IndexController',
            'videos' => $videoRepository->findAll(),
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
    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('front/about.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/professionel", name="professionnel")
     */
    public function professionnel()
    {
        return $this->render('front/professionnel.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/particulier", name="particulier")
     */
    public function particulier()
    {
        return $this->render('front/particulier.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    /**
     * @Route("/video", name="video")
     */
    public function video(VideoRepository $videoRepository)
    {
        return $this->render('front/video.html.twig', [
            'controller_name' => 'IndexController',
            'videos' => $videoRepository->findAll(),
        ]);
    }
}
