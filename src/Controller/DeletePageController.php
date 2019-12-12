<?php

namespace App\Controller;

use App\Entity\ChapterPage;
use App\Entity\LearningModule;
use App\Entity\Chapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeletePageController extends AbstractController
{
    /**
     * @Route("partner/delete/{module}/{chapter}/{page}", name="delete_page", requirements={
     *     "module"="\d+",
     *     "chapter"="\d+",
     *     "page"="\d+"
     * })
     * @param Request $request
     * @param LearningModule $module
     * @param Chapter $chapter
     * @param ChapterPage $page
     * @return Response
     */
    public function index(Request $request,LearningModule $module, Chapter $chapter, ChapterPage $page): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();
        $this->addFlash('success', 'Page deleted successfully!');

        if (isset($_GET['return'])){
            switch ($_GET['return']){
                case 'flow':
                    return $this->redirectToRoute('create_chapter', ['module' => $module->getId(), 'chapter' => $chapter->getId()]);
                case 'dash':
                    return $this->redirectToRoute('dashboard_chapter', ['module' => $module->getId(), 'chapter' => $chapter->getId()]);
                default:
                    return $this->redirectToRoute('partner');
            }
        }
    }
}
