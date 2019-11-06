<?php

namespace App\Controller;

use App\Domain\Badgr;
use App\Entity\LearningModule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        //initialise badgr object
        $badgrObj = new Badgr;
        $user = $this->getUser();

        //For some unholy reason this is required for the rest to work
        $testModule = $this->getDoctrine()->getRepository(LearningModule::class)->find("1");
        $user->addBadge($testModule);

        //get all badges from user
        $badgesData = $user->getBadges();
        $badges = $badgesData->getSnapshot();
        //put all badge keys in userBadges
        $badgeKeys = [];
        foreach ($badges as &$badgeData) {
            $badgeKey = $badgeData->getBadge();
            array_push($badgeKeys, $badgeKey);
        }
        //pass userBadges with keys and the user to the getAllBadges method
        $userBadges = $badgrObj->getAllBadges($badgeKeys, $user);

        //var_dump($userBadges[0]['result']);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'badgeKeys' => $badgeKeys,
            'userBadges' => $userBadges,
            'user' => $user,
        ]);
    }
}
