<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\ChapterPageTranslation;
use App\Entity\Image;
use App\Entity\Language;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/edit/page", name="edit_page")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->find(1); // only english hardcoded for now
        // dropdown menu for language select ??

        // check the $_GET vars, they have to be set, and integers, if not, redirects back to partner
        if (isset($_GET['page'], $_GET['chapter']) && ctype_digit((string)$_GET['page']) && ctype_digit((string)$_GET['chapter'])) {
            //get this Module
            $pageID = $_GET['page'];
            $chapterID = $_GET['chapter'];
        } else {
            return $this->redirectToRoute('partner');
        }

        $page = $this->getDoctrine()->getRepository(ChapterPage::class)->find($pageID);
        $chapter = $this->getDoctrine()->getRepository(Chapter::class)->find($chapterID);
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($chapter->getLearningModule());
        $pageTl = $this->getDoctrine()->getRepository(ChapterPageTranslation::class)->findOneBy(['language' => $language, 'chapterPage' => $page]);

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
            ->add('submit_changes', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $pageTl->setTitle($form->getData()['title']);
            $pageTl->setContent($form->getData()['editor']);
            $page->addTranslation($pageTl);
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
            return $this->redirectToRoute('edit_chapter', ['module' => $module->getId(), 'chapter' => $chapterID]);
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
        ]);
    }
}
