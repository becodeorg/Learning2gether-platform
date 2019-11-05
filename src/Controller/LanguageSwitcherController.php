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

//whatIsHappening();


class LanguageSwitcherController extends AbstractController
{

    /**
     * @Route("/languageswitcher", name="language_switcher")
     */
    public function index(Request $request)
    {
        var_dump($request -> getLocale()); // initial locale

        $switcher = $this->createForm(LanguageSwitcherType::class, null, [
            'action' => $this->generateUrl('language_switcher'),
            'method' => 'POST',
        ]);

        $switcher->handleRequest($request);

        $newLanguageCode = mb_strtolower($_POST['language_switcher']['language']); // new lang code
        $newLanguage = $this->getDoctrine()->getRepository(Language::class)
            ->findOneBy(['code' => $newLanguageCode]);
        var_dump($newLanguage->getCode());

        //if language is changed
        if ($switcher->isSubmitted() && $switcher->isValid()){
            // check first if the user logged in
            // if user is logged in,
            if(!is_null($this->getUser())) {
                $user = $this->getUser();
                // Update User's language in DB with new chosen one
                $user->setLanguage($newLanguage);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } else {
                setcookie('language', $newLanguageCode);
            }
            var_dump($_SERVER['HTTP_REFERER']); // URL before
            var_dump($request -> getLocale()); // locale before
            // URL for redirecting (replace url(language code) with new one )
            $pattern = '/\/[a-z]{2}\//';
            preg_match($pattern, $_SERVER['HTTP_REFERER'], $matches);
            var_dump($matches[0]); // is the language part in url include '/' ex) /en/
            $newURL = (str_replace($matches[0],'/'.$newLanguageCode.'/' ,$_SERVER['HTTP_REFERER']));
            var_dump($newURL); // URL later
            // set the local with new code
            $request->setLocale($newLanguageCode); // change locale in request as wel
            var_dump($request->getLocale());//locale after

            return $this->redirect($newURL);
        }

        //if not
        return $this->redirectToRoute('app_portal', [
            '_locale' => $request -> getLocale()
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
