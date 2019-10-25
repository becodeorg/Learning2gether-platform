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


        /* TEST ISSUER
        $badge = "vUJPtBepTj6GhUmf9HLjiQ";

        $response = $httpClient->request('GET', 'https://api.badgr.io/v2/issuers/vUJPtBepTj6GhUmf9HLjiQ/badgeclasses', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer YWKBzcHMvKoiLjb8I8TjJZ7Bkdhl3F',
            ],
        ]);

        $badgr = $response->toArray();

        var_dump($badgr);


        $recipientObj = (object) [
            'recipient' => [
                'type' => 'email',
                'hashed' => false,
                'identity' => 'broostim@hotmail.be',
            ]
        ];

        $recipient = json_encode($recipientObj);
        */

        //$assertion = $response->toArray();
        //var_dump($assertion);
        //print_r($assertion);

        $testModule = new LearningModule();
        $testModule->setBadge('wlH8g9ALTyyG6YKpheVsuw');
        $testModule->setIsPublished(true);

        $badgrObj = new Badgr;
        /* WORKS
        $badgrObj->addBadgeToUser($testModule, $this->getUser());
        */
        //$badgrObj->getAllBadges($this->getUser());

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            //'badgr' => $badgr,
        ]);
    }
}
