<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\LanguageTrait;
use App\Domain\PageManager;
use App\Entity\ChapterPage;
use App\Entity\LearningModule;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModuleController extends AbstractController
{
    use LanguageTrait;

    /**
     * @Route("/portal/module/{module}", name="module", requirements={"module" = "\d+"})
     */
    public function module(Request $request, LearningModule $module): Response
    {
        //user = logged in user
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('module/index.html.twig', [
            'language' => $this->getLanguage($request),
            'module' => $module
        ]);
    }

    /**
     * @Route("/portal/module/view-page/{chapterPage}/", name="module_view_page", requirements={"page" = "\d+"})
     */
    public function viewPage(Request $request, ChapterPage $chapterPage): Response
    {
        $pageManager = new PageManager($chapterPage);

        return $this->render('module/view-page.html.twig', [
            'language' => $this->getLanguage($request),
            'page' => $chapterPage,
            'pageManager' => $pageManager,
            'env' => $_SERVER['APP_ENV']
        ]);
    }
}