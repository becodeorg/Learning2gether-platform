<?php

namespace App\Controller;

use App\Entity\ChapterPage;
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
        $pageTl = $page->getTranslations()->getValues()[0]; // the 0 is the language id -1 (array) english hardcoded for now

        $form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'data' => $pageTl->getTitle(), // ignore warning, it works
            ])
            ->add('editor', TextareaType::class, [
                'data' => $pageTl->getContent(), // ignore warning, it works
            ])
            ->add('submit_changes', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        return $this->render('edit_page/index.html.twig', [
            'controller_name' => 'EditPageController',
            'page' => $page,
            'pageTl' => $pageTl,
            'form' => $form->createView(),
        ]);
    }
}
