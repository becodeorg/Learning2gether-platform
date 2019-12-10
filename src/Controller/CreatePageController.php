<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreatePageController extends AbstractController
{
    /**
     * @Route("partner/create/module/{module}/chapter/{chapter}/page", name="create_page", requirements={
     *     "module" = "\d+",
     *     "chapter" = "\d+"
     * })
     * @param LearningModule $module
     * @param Chapter $chapter
     * @return Response
     */
    public function index(LearningModule $module, Chapter $chapter): Response
    {
        return $this->render('create_page/index.html.twig', [
            'module' => $module,
            'chapter' => $chapter,
        ]);
    }
}
