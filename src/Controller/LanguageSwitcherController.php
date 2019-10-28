<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageSwitcherType;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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

        if ($switcher->isSubmitted() && $switcher->isValid()){
            //check if logged in
            if(!is_null($this->getUser())) { // if user is logged in
                // if yes - save the new prefered language

            } else {
                // make a cookie

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
