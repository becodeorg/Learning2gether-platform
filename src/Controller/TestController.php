<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        $user = $this -> getDoctrine()->getRepository(User::class)->findOneBy(['email'=>'bona.kim@outlook.be']);
        $product = new Product();
        $product -> setName('kiwi');
        $product -> setUser($user);
        $this -> getDoctrine()->getManager() ->persist($product); // commit
        $this -> getDoctrine()->getManager() ->flush(); // push

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }


    /**
     * @Route("/test/view/{user}/{product}", name="test_view")
     */
    public function view(User $user, Product $product)
    {
        return $this->render('test/view.html.twig', [
            'controller_name' => 'TestController',
            'email' => $user->getEmail(),
            'product' =>  $product,
        ]);
    }
}
