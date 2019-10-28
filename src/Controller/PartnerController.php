<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Language;
use App\Entity\LearningModule;
use App\Entity\LearningModuleTranslation;
use App\Form\CreateModuleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnerController extends AbstractController
{
    /**
     * @Route("/partner", name="partner")
     * @param Request $request
     * @return Response
     */
    public function Index(Request $request): Response
    {
        $languageAll = $this->getDoctrine()->getRepository(Language::class)->findAll();
        $module = new LearningModule('', '', '');
        $translationArray = []; // making a new empty array

        // collect all different languages fro the DB
        foreach ($languageAll as $language) {
            $languageDoctrine = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $language->getCode()]);
            $translation = new LearningModuleTranslation($module, $languageDoctrine);
            $translationArray[] = $translation;
            $module->addTranslation($translation);
        }

        // render the form
        $form = $this->createForm(CreateModuleType::class, $module);
        $form->handleRequest($request);

        // The code below is probably going to give Koen a heart attack, but somehow it works
        // Var names might need refactoring, for sure
        // check if the form is submitted/posted
        if ($form->isSubmitted() && $form->isValid()) {
            $newTranslations = $_POST['create_module']['translations'];
            // check if at least one of the languages are fully filled in
            if ($this->isOneTranslationFilledIn($newTranslations)) {
                $tempArray = [];
                // separate the post values to separate arrays
                foreach ($newTranslations as $key => $translation) {
                    $tempArray['title'] = $translation['title'];
                    $tempArray['description'] = $translation['description'];
                    $translationArray[$key]->setTitle($tempArray['title']);
                    $translationArray[$key]->setTitle($tempArray['description']);
                }
                // add all translations to the new module
                foreach ($translationArray as $translation) {
                    $module->addTranslation($translation);
                }
                // flush the new module to the DB (the translations are set to cascade with this persist)
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($module);
                $entityManager->flush();
            } else {
                echo 'Please fill in at least one language!';
            }
        }

        return $this->render('partner/index.html.twig', [
            'controller_name' => 'PartnerController',
            'form' => $form->createView(),
        ]);
    }

    //  function to check if at least one of the translations is filled in (both fields)
    public function isOneTranslationFilledIn($translations): bool
    {
        foreach ($translations as $translation) {
            if (!empty($translation['title']) && !empty($translation['description'])) {
                return true;
            }
        }
        return false;
    }
}
