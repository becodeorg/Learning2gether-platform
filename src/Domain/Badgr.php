<?php
declare(strict_types=1);

namespace App\Domain;

use App\Entity\LearningModule;
use App\Entity\User;
use Symfony\Component\HttpClient\HttpClient;

class Badgr
{
    public function addBadgeToUser(LearningModule $learningModule, User $user)
    {
        //get badge and email for fetch
        $moduleBadge = $learningModule->getBadge();
        $email = $user->getEmail();
        $authorization = "Dm7CrSehEJTtGUooextyrIWDzhwN82";

        //give the badge to email
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', 'https://api.badgr.io/v2/badgeclasses/'.$moduleBadge.'/assertions', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$authorization,
            ],
            'json' => [
                'recipient' => [
                    'type' => 'email',
                    'hashed' => false,
                    'identity' => $email
                ]
            ]
        ]);
    }

    public function getAllBadges($badges, User $user)
    {
        //get mail from user
        $email = $user->getEmail();
        $userBadges = [];
        $authorization = "Dm7CrSehEJTtGUooextyrIWDzhwN82";

        //var_dump($badges);

        //get badges from email user
        $httpClient = HttpClient::create();
        foreach ($badges as &$badgeKey) {
            $response = $httpClient->request('GET', 'https://api.badgr.io/v2/badgeclasses/'.$badgeKey, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$authorization,
                ],
                'json' => [
                    'recipient' => [
                        'type' => 'email',
                        'hashed' => false,
                        'identity' => $email
                    ]
                ]
            ]);

            //put each badge in array userBadges
            $badgeData = json_decode($response->getContent(), true);
            $userBadges[] = $badgeData;
        }

        return $userBadges;
    }
}