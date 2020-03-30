<?php

namespace App\Controller;

use App\Domain\ImageManager;
use App\Entity\Language;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthAuthenticator;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{


    /**
     * @Route("/register", name="en_register")
     */
    public function registerInEnglish()
    {
        return $this->redirectToRoute('app_register');
    }
    /**
     * @Route("/{_locale}/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute($this->getUser()->isPartner() ? 'partner' : 'portal');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // compare pwd and pwd-repeat
            $pwd = $_POST['registration_form']['plainPassword'];
            $pwdRepeat = $_POST['registration_form']['passwordRepeat'];

            if (!$pwd || !$pwdRepeat || ($pwd != $pwdRepeat)) {
                $this->addFlash('error', 'Enter your password correctly again!');
                return $this->redirect($_SERVER['HTTP_REFERER']);
            }

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $defaultLang = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => 'en']);
            $user->setLanguage($defaultLang);

            // TODO pass null to database to get automatic timestamp
            $dateTime = new DateTimeImmutable();
            $user->setCreated($dateTime);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
