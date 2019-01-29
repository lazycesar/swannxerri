<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MemberAreaController extends AbstractController
{
    /**
     *  @Route("/membres", name="membre")
     */
    public function index(VideoRepository $videoRepository, BlogRepository $blogRepository, Request $request) : Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $video->setDatePublication(new \DateTime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($video);
            $entityManager->flush();

            // return $this->redirectToRoute('video_index');
        }

        return $this->render('front/membres.html.twig', [
            'videos' => $videoRepository->findAll(),
            'form' => $form->createView(),

        ]);
        // return $this->render('front/membres.html.twig', [
        //     'membres' => $blogRepository->findAll(),
        // ]);
    }
}