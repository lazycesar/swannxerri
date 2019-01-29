<?php

namespace App\Controller;

use App\Entity\Ebook;
use App\Form\EbookType;
use App\Repository\EbookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adminswann/ebook")
 */
class EbookController extends AbstractController
{
    /**
     * @Route("/", name="ebook_index", methods={"GET"})
     */
    public function index(EbookRepository $ebookRepository) : Response
    {
        return $this->render('ebook/index.html.twig', [
            'ebooks' => $ebookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ebook_new", methods={"GET","POST"})
     */
    public function new(Request $request, \Swift_Mailer $mailer) : Response
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
                ->attach(\Swift_Attachment::fromPath('assets/ebook/charisme.pdf'));

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

            return $this->redirectToRoute('home');
        }

        return $this->render('ebook/new.html.twig', [
            'ebook' => $ebook,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ebook_show", methods={"GET"})
     */
    public function show(Ebook $ebook) : Response
    {
        return $this->render('ebook/show.html.twig', [
            'ebook' => $ebook,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ebook_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ebook $ebook) : Response
    {
        $form = $this->createForm(EbookType::class, $ebook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ebook_index', [
                'id' => $ebook->getId(),
            ]);
        }

        return $this->render('ebook/edit.html.twig', [
            'ebook' => $ebook,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ebook_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ebook $ebook) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $ebook->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ebook);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ebook_index');
    }
}
