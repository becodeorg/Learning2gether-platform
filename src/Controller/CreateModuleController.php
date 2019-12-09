<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\ImageManager;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Entity\User;
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
        /* @var User $user */
        $user = $this->getUser();

        // initialize new module and one translation in english
        $module = new LearningModule();
        $english = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => 'en']);
        $newTrans = new LearningModuleTranslation($module, $english);
        $module->addTranslation($newTrans);

        // create the form
        $form = $this->createForm(CreateModuleType::class, $module);
        $form->handleRequest($request);

        // check if the form is submitted/posted
        if ($form->isSubmitted() && $form->isValid()) {
                $module = $form->getData();
                $module = $this->makeTranslations($module);
                $this->flushNewModule($module);
                return $this->redirectToRoute('edit_module', ['module' => $module->getId()]);
        }

        return $this->render('create_module/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param LearningModule $module
     * @return LearningModule
     */
    public function makeTranslations(LearningModule $module): LearningModule
    {
        $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        foreach ($languageAll as $language) {
            if ($language->getCode() !== 'en'){
                $translation = new LearningModuleTranslation($module, $language);
                $module->addTranslation($translation);
            }
        }
        return $module;
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
}
