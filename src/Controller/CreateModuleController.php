<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\ImageManager;
use App\Entity\Image;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Form\CreateModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateModuleController extends AbstractController
{
    /**
     * @Route("/partner/create/module", name="create_module")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $imageManager = new ImageManager();

        // make a new module for the form, empty values for now
        $module = new LearningModule('', '', '');
        $translationArray = $this->makeTranslations($module);

        // create the form
        $form = $this->createForm(CreateModuleType::class, $module);
        $form->handleRequest($request);

        // check if the form is submitted/posted
        if ($form->isSubmitted() && $form->isValid()) {
            $newTranslations = $_POST['create_module']['translations'];

            if ($this->isOneTranslationFilledIn($newTranslations)) {
                $module = $form->getData();
                $imageManager->fixUploadsFolder($this->getParameter('uploads_directory'), $this->getParameter('public_directory'));
                $newImage = $imageManager->createImage($request->files->get('create_module')['image'], $this->getUser(), $this->getParameter('uploads_directory'), 'module');
                $this->flushNewImage($newImage);
                $module->setImage($newImage->getSrc());
                $this->flushNewModule($module);
                return $this->redirectToRoute('edit_module', ['module' => $module->getId()]);
            }
            $this->addFlash('error', 'please fill in at least one language');
        }

        return $this->render('create_module/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function isOneTranslationFilledIn(array $translations): bool
    {
        //  function to check if at least one of the translations is filled in (both fields)
        foreach ($translations as $translation) {
            if (!empty($translation['title']) && !empty($translation['description'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param LearningModule $module
     * @return array
     */
    public function makeTranslations(LearningModule $module): array
    {
        $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        $translationArray = [];
        foreach ($languageAll as $language) {
            $languageDoctrine = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $language->getCode()]);
            $translation = new LearningModuleTranslation($module, $languageDoctrine);
            $translationArray[] = $translation;
            $module->addTranslation($translation);
        }
        return $translationArray;
    }

    /**
     * @param LearningModule $module
     */
    public function flushNewModule(LearningModule $module): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($module);
        $entityManager->flush();
    }

    public function flushNewImage(Image $image): void
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();
    }
}
