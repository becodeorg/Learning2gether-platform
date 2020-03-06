<?php

namespace App\Controller;

use App\Entity\PwdResetToken;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class PasswordResetController extends AbstractController
{
    /**
     * @Route("/password-reset", name="password_reset")
     */
    public function index(Swift_Mailer $mailer)
    {
        if (!isset($_POST["reset-request-submit"])) {
            return $this->render('password_reset/index.html.twig');
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $_POST["email"]]);
        if (!$user) {
            $this->addFlash('error', 'Please enter your correct email address again!');
            return $this->render('password_reset/index.html.twig');
        }
        // Create Password reset token & URL
        $url = $this->createPwdResetToken($user);
        // Send an E-mail to the user
        if ($this->sendPwdResetEmail($user, $url, $mailer) > 0) {
            // If everything is done, show the msg and take user to login page
            $this->addFlash(
                'info',
                'Email is sent, check your mail box!'
            );
        } else {
            // If everything is done, show the msg and take user to login page
            $this->addFlash(
                'error',
                'Email cannot be sent.'
            );
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/password-new", name="password_new")
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $selector = $request->query->get('selector');
        $validator = $request->query->get('validator');

        // Throw error to the user with wrong URL
        if (!isset($selector) || !isset($validator)) {
            $this->addFlash('error', 'Your request is not valid, please make new request again!');
            return $this->redirectToRoute('password_reset');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //step0 : Hold all necessary data - selector,validator,pwd,pwd-repeat,current date
            $selector = $form->getData()['selector'];
            $validator = $form->getData()['validator'];
            $pwd = $_POST['reset_password']['password'];
            $pwdRepeat = $_POST['reset_password']['passwordRepeat'];
            $currentDate = date("U");

            //step1 : Input validation (not empty? both are same?)
            if (!$pwd || !$pwdRepeat || ($pwd != $pwdRepeat)) {
                $this->addFlash('error', 'Enter your password correctly again!');
                return $this->redirect($_SERVER['HTTP_REFERER']);
            }

            //step2 : Find the right token from DB (using selector)
            $token = $this->getDoctrine()->getRepository(PwdResetToken::class)->findOneBy(['selector' => $selector]);

            if (!$token) {
                $this->addFlash('error', 'invalid request, please request new mail to reset your password!');
                return $this->render('password_reset/index.html.twig');
            }

            //step3 : check the token is expired or not
            if ($currentDate > $token->getExpires()) {
                $this->addFlash(
                    'error',
                    'your request is expired, please request new mail to reset your password!'
                );
                return $this->render('password_reset/index.html.twig');
            }

            //step4 : Find the user of that token and validate
            if (!password_verify(hex2bin($validator), $token->getToken())) {
                $this->addFlash(
                    'error',
                    'invalid request, please request new mail to reset your password!'
                );
                return $this->render('password_reset/index.html.twig');
            }

            //step 5 : Update the password with new
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $token->getUser()->getID()]);
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);

            //step5 : Remove the token
            $em->remove($token);
            $em->flush();

            $this->addFlash(
                'success',
                'Updated your password successfully, ' . $user->getName()
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('password_reset/create-new-password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }


    private function createPwdResetToken(User $user): string
    {
        $em = $this->getDoctrine()->getManager();

        // Clean up old tokens if it exist
        $oldTokens = $this->getDoctrine()->getRepository(PwdResetToken::class)->findby(['user' => $user->getID()]);

        if ($oldTokens) {
            foreach ($oldTokens as $oldToken) {
                $em->remove($oldToken);
            }
        }
        // We have to flush because we removed all old tokens
        $em->flush();

        // Create and save new tokens & return URL with them
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32); // for user authentication
        $url = $_SERVER["HTTP_ORIGIN"] . "/password-new?selector=" . $selector . "&validator=" . bin2hex($token);
        $pwdToken = new PwdResetToken($user, $selector, $token);
        $em->persist($pwdToken);
        $em->flush();

        return $url;
    }

    private function sendPwdResetEmail(User $user, String $url, Swift_Mailer $mailer)
    {
        $message = (new Swift_Message('Reset Password'))
            ->setFrom('learning2gether@becode.org')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'password_reset/pwdmail.html.twig',
                    ['name' => $user->getName(), 'url' => $url]
                ),
                'text/html'
            );
        return $mailer->send($message);
    }
}
