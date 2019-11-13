<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\LearningModule;
use App\Form\EditChapterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditChapterController extends AbstractController
{
    /**
     * @Route("/edit/chapter", name="edit_chapter")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $module = $this->getDoctrine()->getRepository(LearningModule::class)->find($_GET['module']);
        $chapter = $this->getDoctrine()->getRepository(Chapter::class)->find($_GET['chapter']);

        $form = $this->createForm(EditChapterType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $updatedChapter = $form->getData();
            $this->flushUpdatedChapter($updatedChapter);
        }

        return $this->render('edit_chapter/index.html.twig', [
            'controller_name' => 'EditChapterController',
            'module' => $module,
            'chapter' => $chapter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param $updatedChapter
     */
    public function flushUpdatedChapter($updatedChapter): void
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($updatedChapter);
        $em->flush();
    }
}
