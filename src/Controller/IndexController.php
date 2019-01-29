<?php

namespace App\Controller;

use App\Entity\Ebook;

use App\Form\EbookType;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(VideoRepository $videoRepository, Request $request, \Swift_Mailer $mailer)
    {
        $ebook = new Ebook();
        $form = $this->createForm(EbookType::class, $ebook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ebook);
            $entityManager->flush();

            $body = "<body style='text-align:center'><h3 style='color:red'>Merci d'avoir téléchargé mon ebook. Il se trouve en pièce jointe</h3>
            <p>N'hésitez pas à me faire part de vos commentaires.</p>
            </ body>";

            $message = (new \Swift_Message("Votre Ebook de Swann Xerri"))
                ->setFrom("shinkansen13@gmail.com")
                ->setTo($ebook->getEmail())
                ->setBody(
                    $body,
                    'text/html'
                )
                ->attach(\Swift_Attachment::fromPath('assets/ebook/test-ebook.pdf'));

            $mailer->send($message);

            $newEmail = $ebook->getEmail();
            $content = "<p>Votre ebook a été téléchargé par l'adresse email suivante :</p>
            <p style='color:red'>$newEmail</p>";

            $confirmation = (new \Swift_Message("Téléchargement de votre Ebook"))
                ->setFrom($ebook->getEmail())
                ->setTo("shinkansen13@gmail.com")
                ->setBody(
                    $content,
                    'text/html'
                );

            $mailer->send($confirmation);

            return $this->redirectToRoute('ebook_confirm');
        }

        return $this->render('front/home.html.twig', [
            'controller_name' => 'IndexController',
            'videos' => $videoRepository->findAll(),
            'ebook' => $ebook,
            'form' => $form->createView(),
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
     * @Route("/professionnel", name="professionnel")
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
    /**
     * @Route("/ebook_confirm", name="ebook_confirm")
     */
    public function ebookConfirm()
    {
        return $this->render('confirmation-ebook.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

}
