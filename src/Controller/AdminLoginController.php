<?php

namespace App\Controller;

use App\Entity\AdminLogin;
use App\Form\AdminLoginType;
use App\Repository\AdminLoginRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adminswann/login")
 */
class AdminLoginController extends AbstractController
{
    /**
     * @Route("/", name="admin_login_index", methods={"GET"})
     */
    public function index(AdminLoginRepository $adminLoginRepository) : Response
    {
        return $this->render('admin_login/index.html.twig', [
            'admin_logins' => $adminLoginRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_login_new", methods={"GET","POST"})
     */
    public function new(Request $request) : Response
    {
        $adminLogin = new AdminLogin();
        $form = $this->createForm(AdminLoginType::class, $adminLogin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adminLogin->setCreationDate(new \DateTime);
            $adminLogin->setLevel(1);     // 
            $passwordNonHashe = $adminLogin->getPassword();

            $passwordHashe = password_hash($passwordNonHashe, PASSWORD_BCRYPT);
            $adminLogin->setPassword($passwordHashe);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adminLogin);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('admin_login/new.html.twig', [
            'admin_login' => $adminLogin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_login_show", methods={"GET"})
     */
    public function show(AdminLogin $adminLogin) : Response
    {
        return $this->render('admin_login/show.html.twig', [
            'admin_login' => $adminLogin,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_login_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AdminLogin $adminLogin) : Response
    {
        $form = $this->createForm(AdminLoginType::class, $adminLogin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_login_index', [
                'id' => $adminLogin->getId(),
            ]);
        }

        return $this->render('admin_login/edit.html.twig', [
            'admin_login' => $adminLogin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_login_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AdminLogin $adminLogin) : Response
    {
        if ($this->isCsrfTokenValid('delete' . $adminLogin->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adminLogin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_login_index');
    }
}
