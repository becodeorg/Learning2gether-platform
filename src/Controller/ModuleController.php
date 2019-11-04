<?php
declare(strict_types=1);

namespace App\Controller;

use App\Domain\Badgr;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModuleController extends AbstractController
{
    /**
     * @Route("/module", name="app_module")
     */
    public function module(): Response
    {
        //get a Module
        $module = $_GET['module'] ?? null;
        //$module = $this->getDoctrine()->getRepository(LearningModule::class)->find("2");

        //initialise badgr object
        $badgrObj = new Badgr;
        // user = logged in user
        $user = $this->getUser();

        //when module completed, give badge
        $completed = false;
        if($completed === true){
            //add badge from testModule to user
            $badgrObj->addBadgeToUser($module, $user);
            $user->addBadge($module);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('module/index.html.twig', [
            'controller_name' => 'ModuleController',
            'module' => $module, // module ID
        ]);
    }
}
