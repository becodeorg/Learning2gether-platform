<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageSwitcherType;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LanguageSwitcherController extends AbstractController
{
    const DEFAULT_LANGUAGE = 'en';

    /**
     * @Route("/languageswitcher", name="language_switcher")
     */
    public function index(Request $request)
    {
        $switcher = $this->createForm(LanguageSwitcherType::class, null, [
            'action' => $this->generateUrl('language_switcher'),
            'method' => 'POST',
        ]);

        $switcher->handleRequest($request);

        //if language is NOT changed
        if (!$switcher->isSubmitted() || !$switcher->isValid()) {
            $this->redirect($_SERVER['HTTP_REFERER']);
        }

        /** @var Language $newLanguage */
        $newLanguage = $switcher->getData()['language'];

        // check first if the user logged in
        // if user is logged in,
        if (!is_null($this->getUser())) {
            // Update User's language in DB with new chosen one
            $this->getUser()->setLanguage($newLanguage);
            $this->getDoctrine()->getManager()->flush();
        }
        setcookie('language', $newLanguage->getCode(), time()+60*60*24*365, '/',$_SERVER['HTTP_HOST']);

        // URL for redirecting (replace url(language code) with new one
        $newURL = str_replace('/' . $request->getLocale() . '/',
            '/' . $newLanguage->getCode() . '/',
            $_SERVER['HTTP_REFERER']);
        // set the local with new code

        return $this->redirect($newURL);
    }

    /**
     * This function is called in the header on every page for the language dropdown
     */
    public function getLanguageSwitcherForm(): FormView
    {
        $switcher = $this->createForm(LanguageSwitcherType::class, null, [
            'action' => $this->generateUrl('language_switcher'),
            'method' => 'POST',
        ]);

        if (!is_null($this->getUser())) { // if user is logged in
            $switcher->get('language')->setData($this->getUser()->getLanguage()); // not to set, just to show
        } else {
            //change this so it uses the code
            $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $_COOKIE['language'] ?? self::DEFAULT_LANGUAGE]); // TODO fr
            $switcher->get('language')->setData($language); // not to set, just to show
        }

        return $switcher->createView();
    }
}
