<?php

namespace App\Controller;

use App\Domain\Badgr;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        $badgrHandler = new Badgr;

        /** @var User $user */
        $user = $this->getUser();

        //get all badges from user
        $badges = $user->getBadges()->getSnapshot();
        //put all badge keys in userBadges
        $badgeKeys = [];
        foreach ($badges as &$badgeData) {
            $badgeKey = $badgeData->getBadge();
            array_push($badgeKeys, $badgeKey);
        }
        //pass userBadges with keys and the user to the getAllBadges method
        $userBadges = $badgrHandler->getAllBadges($badgeKeys, $user);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'badgeKeys' => $badgeKeys,
            'userBadges' => $userBadges,
            'user' => $user,
        ]);
    }
}
