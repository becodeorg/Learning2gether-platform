<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\ChapterPage;
use App\Entity\ChapterPageTranslation;
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
        $page = $this->getDoctrine()->getRepository(ChapterPage::class)->find($_GET['page']);
        $chapter = $this->getDoctrine()->getRepository(Chapter::class)->find($_GET['chapter']);
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($chapter->getLearningModule());
        $language = $this->getDoctrine()->getRepository(Language::class)->find(1); // only english hardcoded for now
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
            $pageTl->setId($pageTl->getId());
            $page->addTranslation($pageTl);
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();
        }

        return $this->render('edit_page/index.html.twig', [
            'controller_name' => 'EditPageController',
            'page' => $page,
            'pageTl' => $pageTl,
            'form' => $form->createView(),
            'chapter' => $chapter,
            'module' => $module,
        ]);
    }
}
