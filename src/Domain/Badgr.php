<?php
declare(strict_types=1);

namespace App\Domain;

use App\Entity\LearningModule;
use App\Entity\User;
use Symfony\Component\HttpClient\HttpClient;

class Badgr
{
    //this function is temporary so my (Tim) personal password of badgr doesn't get revealed
    public function getPassword()
    {
        $password = "LearningSoMuchTogether";
        return $password;
    }

    public function initialise(string $password)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', 'https://api.badgr.io/o/token', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' =>[
                'username' => 'koen@becode.org',
                'password' => $password,
            ]
        ]);

        $tokenData = json_decode($response->getContent(), true);

        $_SESSION['refreshToken'] = $tokenData['refresh_token'];
        $_SESSION['accessToken'] = $tokenData['access_token'];

        return $tokenData;
    }

    public function getTokenData(string $refreshToken)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', 'https://api.badgr.io/o/token', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' =>[
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]
        ]);

        $tokenData = json_decode($response->getContent(), true);

        $_SESSION['refreshToken'] = $tokenData['refresh_token'];
        $_SESSION['accessToken'] = $tokenData['access_token'];

        return $tokenData;
    }

    public function addBadgeToUser(LearningModule $learningModule, User $user, string $accessToken)
    {
        //get badge and email for fetch
        $moduleBadge = $learningModule->getBadge();
        $email = $user->getEmail();

        //give the badge to email
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', 'https://api.badgr.io/v2/badgeclasses/'.$moduleBadge.'/assertions', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$accessToken,
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

    public function getAllBadges($badges, User $user, string $accessToken)
    {
        //get mail from user
        $email = $user->getEmail();
        $userBadges = [];

        //var_dump($badges);

        //get badges from email user
        $httpClient = HttpClient::create();
        foreach ($badges as &$badgeKey) {
            $response = $httpClient->request('GET', 'https://api.badgr.io/v2/badgeclasses/'.$badgeKey, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$accessToken,
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