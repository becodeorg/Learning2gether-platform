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
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('email'=>'nicolereyesgg@hotmail.com'));
        $product = new Product;
        $product->setName('banana');
        $product->setUser($user);
        $this->getDoctrine()->getManager()->persist($product); //it will keep it forever
        $this->getDoctrine()->getManager()->flush(); //like git commit => flush


        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    /**
     * @Route("/test/view/{user}/{product}", name="test_view")
     */

    // route name has to be unique

    public function view(User $user, Product $product) //type hint
    {
        //hoover over it alt shift enter
        return $this->render('test/view.html.twig', [
            'controller_name' => 'TestController',
            'email' => $user->getEmail(),
            'product' => $product,
        ]);





    }

}
