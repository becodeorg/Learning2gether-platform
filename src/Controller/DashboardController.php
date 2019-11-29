<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Language;
use App\Entity\LearningModule;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("partner/dashboard", name="dashboard")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code' => $_COOKIE['language'] ?? 'en']);
        $allModules = $this->getDoctrine()->getRepository(LearningModule::class)->findAll();
        $allLanguages = $this->getDoctrine()->getRepository(Language::class)->findAll();

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT lm FROM App\Entity\LearningModule lm LEFT JOIN lm.chapters c');
        $result = $query->getResult();

        foreach ($result as $item) {
            var_dump($item->getChapters()->getPages());
        }

        // TODO FIX THIS MESS
        var_dump($result);

        return $this->render('dashboard/index.html.twig', [
            'result' => $result,
            'allModules' => $allModules,
            'allLanguages' => $allLanguages,
            'language' => $language,
        ]);
    }
}
