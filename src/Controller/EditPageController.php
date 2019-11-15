<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\ChapterPageTranslation;
use App\Entity\Image;
use App\Entity\Language;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
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
            'code' => $request->getLocale()
        ]);

        $page = $this->getDoctrine()->getRepository(ChapterPage::class)->find($page);
        $chapter = $this->getDoctrine()->getRepository(Chapter::class)->find($chapter);
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($module);
        $pageTl = $this->getDoctrine()->getRepository(ChapterPageTranslation::class)->findOneBy(['language' => $language, 'chapterPage' => $page]);

        // form creator for uploader
//        $uploader = $this->createFormBuilder()
//            ->add('upload', FileType::class)
//            ->add('submit', SubmitType::class)
//            ->getForm();
//
//        $uploader->handleRequest($request);

        $form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'data' => $pageTl->getTitle(),
                'required' => false,
                'empty_data' => '',
            ])
            ->add('editor', TextareaType::class, [
                'data' => $pageTl->getContent(),
                'required' => false,
                'empty_data' => '',
            ])
            ->add('save_changes', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        // code for handling the upload form, if we ever need it
//        if ($uploader->isSubmitted() && $uploader->isValid()){
//            //get upload dir
//            $uploads_directory = $this->getParameter('uploads_directory');
//            $uploadedImage = $uploader->getData()['upload'];
//            $filename = md5(uniqid('', true)) . '.' . $uploadedImage->guessExtension();
//            $newImage = new Image($uploadedImage->getClientOriginalName(), $filename, $this->getUser(), 'content');
//
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($newImage);
//            $em->flush();
//
//            $uploadedImage->move(
//                $uploads_directory,
//                $filename
//            );
//            return $this->redirectToRoute('edit_page', ['chapter' => $chapterID, 'page' => $pageID]);
//        }

        if ($form->isSubmitted() && $form->isValid()) {
            $pageTl->setTitle($form->getData()['title']);
            $pageTl->setContent($form->getData()['editor']);
            $page->addTranslation($pageTl);
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
            $this->addFlash('success', 'Changes saved.');
            return $this->redirectToRoute('edit_page', ['chapter' => $chapterID, 'page' => $pageID]);
        }

        $imagesAll = $this->getDoctrine()->getRepository(Image::class)->findAll();

        return $this->render('edit_page/index.html.twig', [
            'controller_name' => 'EditPageController',
            'page' => $page,
            'pageTl' => $pageTl,
            'form' => $form->createView(),
            'chapter' => $chapter,
            'module' => $module,
            'imagesAll' => $imagesAll,
//            'uploader' => $uploader->createView(),
        ]);
    }
}
