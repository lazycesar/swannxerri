<?php

namespace App\Controller;

use App\Entity\AdminLogin;
use App\Form\AdminLoginType;
use App\Repository\AdminLoginRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * @Route("/signup")
 */
class AdminLoginController extends AbstractController
{
    /**
     * @Route("/index", name="admin_login_index", methods={"GET"})
     */
    public function index(AdminLoginRepository $adminLoginRepository) : Response
    {
        return $this->render('admin_login/index.html.twig', [
            'admin_logins' => $adminLoginRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="admin_login_new", methods={"GET","POST"})
     */
    public function new(Request $request, \Swift_Mailer $mailer) : Response
    {
        $adminLogin = new AdminLogin();
        $form = $this->createForm(AdminLoginType::class, $adminLogin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adminLogin->setCreationDate(new \DateTime);
            $adminLogin->setLevel(0);

            $passwordNonHashe = $adminLogin->getPassword();
            $passwordHashe = password_hash($passwordNonHashe, PASSWORD_BCRYPT);
            $adminLogin->setPassword($passwordHashe);

            $cleActivation = md5(uniqid());
            $adminLogin->setCleActivation($cleActivation);
            $adminLogin->setDateLimiteActivation(new \DateTime("+1 day"));

            $email = $adminLogin->getEmail();
            $urlActivation = $this->generateUrl("activation", [], UrlGeneratorInterface::ABSOLUTE_URL);

            $prenom = $adminLogin->getPrenom();
            $nom = $adminLogin->getNom();
            $body = "<body style='text-align:center'><h3 style='color:red'>Bonjour, $prenom ! Votre demande d'inscription a bien été enregistrée.</h3>
            <p>Veuillez la confirmer en cliquant sur ce lien</p>
            <p><a href='$urlActivation?email=$email&cleActivation=$cleActivation'>ACTIVER MON COMPTE</a></p>
            <p>Merci et à bientôt !</p>
            <p style='font-weight:bold'>Swann</p>
            </body>";

            $message = (new \Swift_Message("Votre adhésion au site de Swann Xerri"))
                ->setFrom("contact@swannxerri.com")
                ->setTo($adminLogin->getEmail())
                ->setBody($body, "text/html");

            $mailer->send($message);

            $adminBody = "<body style='text-align:center'><h3>Bonjour, Swann ! $prenom $nom a acheté votre formation.</h3>
            <p>Veuillez vérifier la bonne exécution de la transaction dans votre compte PayPal, ainsi que la présence de ce nouveau membre dans votre interface administrateur.</p>
            </body>";

            $adminMessage = (new \Swift_Message("Vous avez un nouveau membre"))
                ->setFrom($adminLogin->getEmail())
                ->setTo("shinkansen13@gmail.com")
                ->setBody($adminBody, "text/html");

            $mailer->send($adminMessage);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adminLogin);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('admin_login/new.html.twig', [
            'admin_login' => $adminLogin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activation", name="activation", methods={"GET","POST"})
     */
    public function activation(AdminLoginRepository $adminLoginRepository, Request $request, \Swift_Mailer $mailer) : Response
    {

        $email = $request->get("email");
        $cleActivation = $request->get("cleActivation");

        if (($email != "") && ($cleActivation != "")) {
            $userCherche = $adminLoginRepository->findOneBy(["email" => $email]);
            if ($userCherche) {

                if ($cleActivation == $userCherche->getCleActivation()) {
                    $dateFin = $userCherche->getDateLimiteActivation();
                    $timestampFin = $dateFin->getTimeStamp();
                    $timestampActuel = time();
                    if ($timestampFin > $timestampActuel) {

                        $userCherche->setLevel(1);
                        $userCherche->setCleActivation(md5(uniqid()));
                        $userCherche->setDateLimiteActivation(new \DateTime("+ 1 day"));

                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->flush();

                        $messageConfirmation = "Merci, votre compte est désormais actif.";
                        return $this->redirectToRoute('membre');
                    } else {
                        $messageConfirmation = "La date de validité de la clé d'activation a expiré. Veuillez recommencer votre inscription.";
                    }
                }
            } else {
                $messageConfirmation = "Désolé, Vous n'êtes pas dans notre base de données.";
            }
        }

        dump($_REQUEST);
        dump(" $email, $cleActivation ");

        return $this->render('admin_login/activation.html.twig', [
           // 'articleFooter' => $articleFooter,
            'email' => $email,
            'cleActivation' => $cleActivation,
            'messageConfirmation' => $messageConfirmation ?? "",
        ]);
    }

    /**
     * @Route("/lostpw", name="lostpw", methods={"GET","POST"})
     */
    public function lostpw(Request $request, AdminLoginRepository $adminLoginRepository, ObjectManager $manager, \Swift_Mailer $mailer) : Response
    {
        $email = $request->get("email");
        $adminLogin = null;
        if ($email != "") {
            $adminLogin = $adminLoginRepository->findOneBy(["email" => $email]);
        }
        if ($adminLogin != null) {

            $cleActivation = md5(uniqid());
            $adminLogin->setCleActivation($cleActivation);
            $adminLogin->setDateLimiteActivation(new \DateTime("+1 day"));

            $email = $adminLogin->getEmail();
            $urlActivation = $this->generateUrl("resetpw", [], UrlGeneratorInterface::ABSOLUTE_URL);

            $prenom = $adminLogin->getUsername();
            $body = "<body style='text-align:center'><h3>Bonjour, $prenom ! Votre demande de réinitialisation du mot de passe a été prise en compte.</h3>
            <p>Cliquez sur le lien ci-dessous pour accéder au formulaire.</p>
            <p><a href='$urlActivation?email=$email&cleActivation=$cleActivation'>RÉINITIALISER MON MOT DE PASSE</a></p>
            <p>Si vous n'avez pas demandé une réinitialisation du mot de passe, contactez l'administrateur du site.</p>
            <p>Contact : <a href='shinkansen13@gmail.com'>shinkansen13@gmail.com</a></p>
            </body>";

            $adminMessage = (new \Swift_Message("Swann Xerri - Réinitialisation de votre mot de passe"))
                ->setFrom("shinkansen13@gmail.com")
                ->setTo($adminLogin->getEmail())
                ->setBody($body, "text/html");

            $mailer->send($adminMessage);

            $manager->persist($adminLogin);
            $manager->flush();

            $messageConfirmation = "Veuillez vérifier votre boîte mail.";
        }

        return $this->render('admin_login/lostpw.html.twig', [
            'messageConfirmation' => $messageConfirmation ?? "",
        ]);
    }

    /**
     * @Route("/resetpw", name="resetpw", methods={"GET","POST"})
     */
    public function resetpw(Request $request, AdminLoginRepository $adminLoginRepository, ObjectManager $manager, \Swift_Mailer $mailer) : Response
    {
        $email = $request->get("email");
        $cleActivation = $request->get("cleActivation");
        $password = $request->get("password");
        $passwordConfirm = $request->get("password-confirm");

        $adminLogin = null;

        if ($email != "") {
            $adminLogin = $adminLoginRepository->findOneBy(["email" => $email]);
        }

        if ($adminLogin != null) {
            if ($email == $adminLogin->getEmail()) {
                if ($cleActivation == $adminLogin->getCleActivation()) {
                    if ($password != "" && $passwordConfirm != "") {
                        if ($password == $passwordConfirm) {

                            $passwordHashe = password_hash($password, PASSWORD_BCRYPT);
                            $adminLogin->setPassword($passwordHashe);

                            $prenom = $adminLogin->getUsername();
                            $body = "<body style='text-align:center'><h3>Bonjour, $prenom ! Votre mot de passe a été réinitialisé avec succès.</h3>
            <p>Si vous n'êtes pas à l'origine de cette demande, contactez l'administrateur du site.</p>
            <p>Contact : <a href='shinkansen13@gmail.com'>shinkansen13@gmail.com</a></p>
            </body>";

                            $adminMessage = (new \Swift_Message("Swann Xerri - Votre mot de passe a été réinitialisé"))
                                ->setFrom("shinkansen13@gmail.com")
                                ->setTo($adminLogin->getEmail())
                                ->setBody($body, "text/html");

                            $mailer->send($adminMessage);

                            $adminLogin->setPassword($passwordHashe);
                            $manager->persist($adminLogin);
                            $manager->flush();

                            $messageConfirmation = "Votre mot de passe a été réinitialisé avec succès.";
                        }
                    }
                }
            }
        }

        return $this->render('admin_login/resetpw.html.twig', [
            'email' => $email,
            'cleActivation' => $cleActivation,
            'messageConfirmation' => $messageConfirmation ?? "",
        ]);

    }

    /**
     * @Route("/paiement-confirmation", name="payment_confirm", methods={"GET","POST"})
     */
    public function paymentConfirm() : Response
    {

        return $this->render('admin_login/payment-confirm.html.twig', []);
    }

    /**
     * @Route("/index/show/{id}", name="admin_login_show", methods={"GET"})
     */
    public function show(AdminLogin $adminLogin) : Response
    {
        return $this->render('admin_login/show.html.twig', [
            'admin_login' => $adminLogin,
        ]);
    }

    /**
     * @Route("/index/edit/{id}", name="admin_login_edit", methods={"GET","POST"})
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
     * @Route("/index/{id}", name="admin_login_delete", methods={" DELETE "})
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
