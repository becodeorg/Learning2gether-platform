<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Language;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/forum/{category}", name="category", requirements={
     *     "category"="\d+",
     *
     *     })
     */
    public function index(Request $request, Category $category)
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(['code'=> $_COOKIE['language'] ?? 'en']);
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class)->find($category);
        $topics = $this->getDoctrine()->getRepository(Chapter::class)->findBy([
            'learningModule' => $categoryRepo->getId()
        ]);

        return $this->render('category/index.html.twig', [
            'category' => $categoryRepo,
            'topics' => $topics,
            'language' => $language,
        ]);
    }


}
