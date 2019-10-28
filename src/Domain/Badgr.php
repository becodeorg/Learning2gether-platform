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
        $moduleBadge = $learningModule->getBadge();
        $email = $user->getEmail();

        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', 'https://api.badgr.io/v2/badgeclasses/'.$moduleBadge.'/assertions', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer VzjoS17oZOSCz9224w2yBgvk30ZXT0',
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
        $email = $user->getEmail();
        $userBadges = [];

        $httpClient = HttpClient::create();
        foreach ($badges as &$badgeKey) {
            $response = $httpClient->request('GET', 'https://api.badgr.io/v2/badgeclasses/'.$badgeKey, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer VzjoS17oZOSCz9224w2yBgvk30ZXT0',
                ],
                'json' => [
                    'recipient' => [
                        'type' => 'email',
                        'hashed' => false,
                        'identity' => $email
                    ]
                ]
            ]);

            $badgeData = json_decode($response->getContent(), true);
            array_push($userBadges, $badgeData);
        }

        var_dump($userBadges);
    }
}