<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => 'janvandevelde.dev@gmail.com']);
        $product = new Product();
        $product->setName('kiwi');
        $product->setUser($user);
        $this->getDoctrine()->getManager()->persist($product); // 'kind of like a git commit, set doctrine to care about $product'
        $this->getDoctrine()->getManager()->flush(); // 'kind of like a git push for doctrine'
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("test/view/{user}/{product}", name="test_view")
     */
    public function view(User $user, Product $product)
    {
        return $this->render('test/view.html.twig', [
            'controller_name' => 'TestController',
            'email' => $user->getEmail(),
            'product' => $product,
        ]);
    }
}
