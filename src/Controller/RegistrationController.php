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
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $defaultLang= $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => 'EN']);
            $user->setLanguage($defaultLang);

            // TODO pass null to database to get automatic timestamp
            $dateTime = new DateTimeImmutable();
            $user->setCreated($dateTime);

            $imageManager = new ImageManager();
            $imageManager->fixUploadsFolder($this->getParameter('uploads_directory'));
            $newImage = $imageManager->createImage($request->files->get('registration_form')['avatar'], $user, $this->getParameter('uploads_directory'), 'avatar');
            $user->setAvatar($newImage->getSrc());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($newImage);
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
