<?php
//name space is a group of classes give differernt categories.  if you give specific name....
namespace App\Controller;

// use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{ // this comment is an annottation... comments that do stuff. reflection/after all is loaded then it will be over it running...inject behavior that would
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
       // $user = new User();

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'name' => 'Joe',
        ]);
    }
}
