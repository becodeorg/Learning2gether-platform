<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\ChapterPageTranslation;
use App\Entity\ChapterTranslation;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Form\CreateChapterPageType;
use App\Form\EditChapterTranslationsType;
use App\Form\EditChapterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditChapterController extends AbstractController
{
    /**
     * @Route("/partner/edit/module/{module}/chapter/{chapter}", name="edit_chapter" , requirements={
     *     "module" = "\d+",
     *     "chapter" = "\d+"
     * })
     * @param Request $request
     * @param LearningModule $module
     * @param Chapter $chapter
     * @return Response
     */
    public function index(Request $request, LearningModule $module, Chapter $chapter): Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy([
            'code' => $_GET['lang']
        ]);

        if ($language === null) {
            return $this->redirectToRoute('partner');
        }

        $chapterTL = $this->getDoctrine()->getRepository(ChapterTranslation::class)->findOneBy(['language' => $language, 'chapter' => $chapter]);

        // Create translation form
        $form = $this->createForm(EditChapterTranslationsType::class, $chapterTL);
        $form->handleRequest($request);

        // check if the form was submitted
        if ($form->isSubmitted() && $form->isValid()) {
            $chapterTL = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($chapterTL);
            $em->flush();
            return $this->redirectToRoute('dashboard_module', ['module' => $module->getId()]);
        }

        return $this->render('edit_chapter/index.html.twig', [
            'module' => $module,
            'chapter' => $chapter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Resorts an item using it's doctrine sortable property
     * @Route("/partner/page/sort/{id}/{position}", name="dashboard_chapter_page_sort", requirements={"id"= "\d+"})
     * @param integer $id
     * @param integer $position
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sortAction($id, $position)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository(ChapterPage::class)->find($id);

        if (!$page) {
            throw $this->createNotFoundException(
                'No page found for id ' . $id
            );
        }
        $page->setPosition($position);
        $em->persist($page);
        $em->flush();

        return new Response(
            "Page $id moved to position : $position",
            Response::HTTP_OK
        );
    }
}
