<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\ChapterPageTranslation;
use App\Entity\Image;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Form\EditPageTranslationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditPageController extends AbstractController
{
    /**
     * @Route("/partner/edit/module/{module}/chapter/{chapter}/page/{page}", name="edit_page", requirements={
     *     "module" = "\d+",
     *     "chapter" = "\d+",
     *     "page" = "\d+"
     * })
     * @param Request $request
     * @param LearningModule $module
     * @param Chapter $chapter
     * @param ChapterPage $page
     * @return Response
     */
    public function index(Request $request, LearningModule $module, Chapter $chapter, ChapterPage $page): Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy([
            'code' => $_GET['lang']
        ]);

        $returnCode = $_GET['return'] ?? null;

        if ($language === null || $returnCode === null) {
            return $this->redirectToRoute('partner');
        }

        $pageTl = $this->getDoctrine()->getRepository(ChapterPageTranslation::class)->findOneBy(['language' => $language, 'chapterPage' => $page]);

        $form = $this->createForm(EditPageTranslationType::class, $pageTl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageTl = $form->getData();
            $page->addTranslation($pageTl);
            $this->flushUpdatedPage($page);
            $this->addFlash('success', 'Changes saved.');

            if (isset($returnCode)) {
                switch ($returnCode) {
                    case 'flow':
                        return $this->redirectToRoute('create_chapter', ['module' => $module->getId(), 'chapter' => $chapter->getId()]);
                    case 'dash':
                        return $this->redirectToRoute('dashboard_chapter', ['module' => $module->getId(), 'chapter' => $chapter->getId()]);
                    default:
                        return $this->redirectToRoute('partner');
                }
            }
            // just in case
            return $this->redirectToRoute('create_chapter', ['module' => $module->getId(), 'chapter' => $chapter->getId()]);
        }

        $imagesAll = $this->getDoctrine()->getRepository(Image::class)->findAll();

        return $this->render('edit_page/index.html.twig', [
            'returnCode' => $returnCode,
            'page' => $page,
            'pageTl' => $pageTl,
            'form' => $form->createView(),
            'imagesAll' => $imagesAll,
        ]);
    }

    /**
     * @param ChapterPage $page
     */
    public function flushUpdatedPage(ChapterPage $page): void
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($page);
        $em->flush();
    }
}
