<?php

namespace App\Controller;

use App\Domain\Badgr;
use App\Entity\LearningModule;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;

//{"access_token": "YWKBzcHMvKoiLjb8I8TjJZ7Bkdhl3F",
// "token_type": "Bearer",
// "expires_in": 86400,
// "refresh_token": "D1R0p1U4bZXRZe45R8Xc0PVXcQ0JWl",
// "scope": "rw:profile rw:issuer rw:backpack"}

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        //initialise badgr object
        $badgrObj = new Badgr;
        $user = $this->getUser();

        //For some unholy reason this is required for the rest to work
        $testModule = $this->getDoctrine()->getRepository(LearningModule::class)->find("2");
        $user->addBadge($testModule);

        //get all badges from user
        $badgesData = $user->getBadges();
        $badges = $badgesData->getSnapshot();
        //put all badge keys in userBadges
        $userBadges = [];
        foreach ($badges as &$badgeData) {
            $badgeKey = $badgeData->getBadge();
            array_push($userBadges, $badgeKey);
        }
        //pass userBadges and the user to the getAllBadges method
        $badgrObj->getAllBadges($userBadges, $user);

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'userBadges' => $userBadges,
        ]);
    }
}
