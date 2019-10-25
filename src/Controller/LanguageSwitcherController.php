<?php

namespace App\Controller;

use App\Form\LanguageSwitcherType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;



class LanguageSwitcherController extends AbstractController
{
    /**
     * @Route("/language/switcher", name="language_switcher")
     */
    public function index()
    {
        $form = $this->createForm(LanguageSwitcherType::class);

        return $this->redirectToRoute('test', [
            '_locale' => mb_strtolower($_POST['language_switcher']['language'])
        ]);
    }
}
