<?php

namespace App\Controller;

use App\Entity\PwdResetToken;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\DBAL\Event\SchemaAlterTableEventArgs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PasswordResetController extends AbstractController
{
    /**
     * @Route("/password-reset", name="password_reset")
     */
    public function index()
    {
        if (!isset($_POST["reset-request-submit"])) {
            return $this->render('password_reset/index.html.twig', [
            ]);
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $_POST["email"]]);
        if(!$user){
            $this->addFlash(
                'info',
                'Please enter your correct email address again!'
            );
            return $this->render('password_reset/index.html.twig');
        }

        $this->createPwdResetToken($user);

        // If everything(token creating; sending email) is fine then
        $this->addFlash(
            'info',
            'Email is sent, check your mail box!'
        );

        return $this->redirectToRoute('app_login');
    }

    private function createPwdResetToken(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        // First, clean up old tokens if it exist
        $oldTokens = $this->getDoctrine()->getRepository(PwdResetToken::class)->findby(['user' => $user->getID()]);

        if ($oldTokens) {
            foreach ($oldTokens as $oldToken) {
                $em->remove($oldToken);
            }
        }
        $em->flush();

        // create new tokens(+ expires) for that email
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32); // for user authentication
        $expires = date("U") + 1800; // 1hour
        $url = "http://l2g.local/index.php/password-new?selector=" . $selector . "&validator=" . bin2hex($token);

        $pwdToken = new PwdResetToken();
        $pwdToken->setUser($user);
        $pwdToken->setSelector($selector);
        $pwdToken->setToken(password_hash($token, PASSWORD_DEFAULT)); // hashed token
        $pwdToken->setExpires($expires);
        $em->persist($pwdToken);
        $em->flush();


        // TODO SEND EMAIL TO USER WITH THE URL
//        $this->sendPwdResetEmail();
    }

    private function sendPwdResetEmail()
    {
       // TODO code to send pwdResetEmail
    }

}