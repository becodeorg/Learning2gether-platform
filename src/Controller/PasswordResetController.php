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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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

    /**
     * @Route("/password-new", name="password_new")
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $selector= $request->query->get('selector');
        $validator= $request->query->get('validator');

        if(!isset($selector) || !isset($validator)){
            $this->addFlash(
                'info',
                'Your request is not valid, please make new request again!'
            );
            return $this->redirectToRoute('password_reset');
        }

//        var_dump(ctype_xdigit($selector));
//        var_dump(ctype_xdigit($selector));

        $form = $this->createForm(resetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //step0 : hold all data I need to update - selector,validator,pwd,pwd-repeat,current date
            $selector = $form ->getData()['selector']; // $_POST['reset_password']['selector']
            $validator = $form ->getData()['validator']; // $_POST['reset_password']['validator']
            $pwd = $_POST['reset_password']['password'];
            $pwdRepeat = $_POST['reset_password']['passwordRepeat'];
            $currentDate = date("U"); //TODO recheck the correct type (string, int)

            //step1 : Input validation (password not empty? are they same?)
            if(!$pwd || !$pwdRepeat || ($pwd!=$pwdRepeat)){
                $this->addFlash(
                    'info',
                    'Enter your password correctly!'
                );
                return  $this->redirect($_SERVER['HTTP_REFERER']);
            }

            //step2 : find exact that token from DB (using selector)
            $token = $this->getDoctrine()->getRepository(PwdResetToken::class)->findOneBy(['selector' => $selector]);

            if(!$token){
                $this->addFlash(
                    'info',
                    'invalid request, please request new mail to reset your password!'
                );
                return $this->render('password_reset/index.html.twig');
            }

            //step3 : check time (token is expired or not), validator
            if($currentDate > $token->getExpires()){
                $this->addFlash(
                    'info',
                    'your request is expired, please request new mail to reset your password!'
                );
                return $this->render('password_reset/index.html.twig');
            }

            //step4 : find the user of that token and update the password with new
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $token->getUser()->getID()]);
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            //step5 : remove the token
            $entityManager->remove($token);
            $entityManager->flush();

            $this->addFlash(
                'info',
                'Updated your password successfully!, '. $user->getName()
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('password_reset/create-new-password.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
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