<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageSwitcherType;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

session_start();

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

whatIsHappening();


class LanguageSwitcherController extends AbstractController
{

    /**
     * @Route("/languageswitcher", name="language_switcher")
     */
    public function index(Request $request)
    {
//        var_dump($request); die;
//        $locale = $request -> getLocale(); // get locale, null? then get default locale
//        var_dump($locale); die;

        $switcher = $this->createForm(LanguageSwitcherType::class, null, [
            'action' => $this->generateUrl('language_switcher'),
            'method' => 'POST',
        ]);

        $switcher->handleRequest($request);

        if ($switcher->isSubmitted() && $switcher->isValid()){ // The language is changed,
            //check if the user logged in
            if(!is_null($this->getUser())) { // if user is logged in
                $user = $this->getUser(); // user is the person who logged in
//                var_dump($user);
//                $language = $user->getLanguage(); // create a new language entity and put in the variable
//                var_dump($language);

                // Find the new language with the submitted code (post) in database
                $newLang = $this->getDoctrine()->getRepository(Language::class)
                    ->findOneBy(['code' => mb_strtolower($_POST['language_switcher']['language'])]); // find the lang class with code '??'($_POST['language_switcher']['language']) and put it in the var $newLang
//                var_dump($newLang);

                // Update User's language with new chosen one
                $user->setLanguage($newLang); // put the Language(with chosen lang) to the user's language
//                var_dump($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

            } else {
//                var_dump("not logged in!");
//                var_dump($request); die;
//                var_dump($request->cookies->all()); // Array of all cookie
                setcookie('language', mb_strtolower($_POST['language_switcher']['language']));
                die;
            }

            //die($_SERVER['HTTP_REFERER']);

            return $this->redirectToRoute('app_portal', [
                '_locale' => mb_strtolower($_POST['language_switcher']['language']) // field from that form
            ]);
        }

        return $this->redirectToRoute('app_portal', [
            '_locale' => 'en' // TODO ask
        ]);
    }

    public function getLanguageSwitcherForm(): FormView
    {
        $switcher = $this->createForm(LanguageSwitcherType::class, null, [
            'action' => $this->generateUrl('language_switcher'),
            'method' => 'POST',
        ]);

        if(!is_null($this->getUser())) { // if user is logged in
            $switcher->get('language')->setData($this->getUser()->getLanguage()); // not to set, just to show
        } else {
            //change this so it uses the code
            $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => 'fr']); //'fr' $_COOKIE['lang']
            $switcher->get('language')->setData($language); // not to set, just to show
        }

        return $switcher->createView();
    }
}
