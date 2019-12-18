<?php

namespace App\Controller;

use App\Domain\LanguageTrait;
use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Language;
use App\Entity\Question;
use App\Repository\TopicRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    use LanguageTrait;
    /**
     * @Route("portal/forum/{category}", name="category", requirements={"category"="\d+"})
     */
    public function index(Request $request, Category $category): Response
    {
        $language = $this->getLanguage($request);
        $topics = $this->getDoctrine()->getRepository(Chapter::class)->findBy([
            'learningModule' => $category->getLearningModule()
        ]);

        $qRepo = $this->getDoctrine()->getRepository(Question::class);

        return $this->render('category/index.html.twig', [
            'qRepo' => $qRepo,
            'category' => $category,
            'topics' => $topics,
            'language' => $language,
        ]);

    }


}
