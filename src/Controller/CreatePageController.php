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
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return Response
     */
    public function index(LearningModule $module, Chapter $chapter, Request $request): Response
    {
        $english = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => 'en']);
        $page = $chapter->createNewPage();
        $pageTranslation = new ChapterPageTranslation($english, $page);

        $form = $this->createForm(EditPageTranslationType::class, $pageTranslation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pageTranslation = $form->getData();
            $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
            $page->addTranslation($pageTranslation);
            foreach ($languageAll as $language) {
                if ($language->getCode() !== $english->getCode()){
                    $emptyTranslation = new ChapterPageTranslation($language, $page);
                    $page->addTranslation($emptyTranslation);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            $this->addFlash('success', 'New page saved successfully!');
            switch ($_POST['button']){
                case 'exit':
                    return $this->redirectToRoute('create_chapter', ['module' => $module->getId(), 'chapter' => $chapter->getId()]);
                case 'next':
                    return $this->redirectToRoute('create_page', ['module' => $module->getId(), 'chapter' => $chapter->getId()]);
                default:
                    return $this->redirectToRoute('partner');
            }
        }

        $imagesAll = $this->getDoctrine()->getRepository(Image::class)->findAll();

        return $this->render('create_page/index.html.twig', [
            'module' => $module,
            'chapter' => $chapter,
            'form' => $form->createView(),
            'english' => $english,
            'imagesAll' => $imagesAll,
        ]);
    }
}
