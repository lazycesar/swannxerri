<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/signu")
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/", name="member_index", methods={"GET"})
     */
    public function index(MemberRepository $memberRepository) : Response
    {
        return $this->render('member/index.html.twig', [
            'members' => $memberRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="member_new", methods={"GET","POST"})
     */
    public function new(Request $request, \Swift_Mailer $mailer) : Response
    {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $member->setDateAdhesion(new \DateTime);
            //$adminLogin->setLevel(1);
            $passwordNonHashe = $member->getPassword();

            $passwordHashe = password_hash($passwordNonHashe, PASSWORD_BCRYPT);
            $member->setPassword($passwordHashe);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($member);
            $entityManager->flush();

            $prenom = $member->getPrenom();
            $body = "<body style='text-align:center'><h3 style='color:red'>Bonjour, $prenom ! Votre inscription a bien été enregistrée.</h3>
            <p>N'hésitez pas à me faire part de vos commentaires.</p>
            <p>Merci et à bientôt !</p>
            <p style='font-weight:bold'>Swann</p>
            </body>";

            $message = (new \Swift_Message("Votre adhésion au site de Swann Xerri"))
                ->setFrom("contact@swannxerri.com")
                ->setTo($member->getEmail())
                ->setBody(
                    $body,
                    'text/html' 
                    // text/plain ne permet pas l'utilisation du HTML !
                );

            $mailer->send($message);

            return $this->redirectToRoute('membre');
        }

        return $this->render('member/new.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="member_show", methods={"GET"})
     */
    public function show(Member $member) : Response
    {
        return $this->render('member/show.html.twig', [
            'member' => $member,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="member_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Member $member) : Response
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('member_index', [
                'id' => $member->getId(),
            ]);
        }

        return $this->render('member/edit.html.twig', [
            'member' => $member,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="member_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Member $member) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $member->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($member);
            $entityManager->flush();
        }

        return $this->redirectToRoute('member_index');
    }
}
