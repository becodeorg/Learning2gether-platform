<?php
declare(strict_types=1);

namespace App\Domain;

use App\Entity\LearningModule;
use App\Entity\User;
use Symfony\Component\HttpClient\HttpClient;

// /v2/badgeclasses/wlH8g9ALTyyG6YKpheVsuw testbadge
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
                'Authorization' => 'Bearer YWKBzcHMvKoiLjb8I8TjJZ7Bkdhl3F',
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

    public function getAllBadges(User $user)
    {

    }
}