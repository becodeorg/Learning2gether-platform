<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/{_locale}/", name="homepage", requirements={"_locale"="\w\w"})
     * @Route("/", name="homepage_default")
     */
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute($this->getUser()->isPartner() ? 'partner' : 'portal');
        }

        return $this->render('homepage/index.html.twig');
    }
}
