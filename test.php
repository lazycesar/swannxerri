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