<?php
declare(strict_types=1);

namespace App\Domain;

use App\Entity\LearningModule;
use App\Entity\User;
use Symfony\Component\HttpClient\HttpClient;

class Badgr
{
    // TODO add these constants to a config file
    private const BADGR_PASSWORD = 'LearningSoMuchTogether';
    private const BADGR_USERNAME = 'koen@becode.org';
    private const BADGR_API = 'https://api.badgr.io';

    public function initialise()
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', self::BADGR_API . '/o/token', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => [
                'username' => self::BADGR_USERNAME,
                'password' => self::BADGR_PASSWORD,
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
        $response = $httpClient->request('POST', self::BADGR_API . '/o/token', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]
        ]);

        $tokenData = json_decode($response->getContent(), true);

        $_SESSION['refreshToken'] = $tokenData['refresh_token'];
        $_SESSION['accessToken'] = $tokenData['access_token'];

        return $tokenData;
    }

    public function addBadgeToUser(LearningModule $learningModule, User $user, string $accessToken): void
    {
        //get badge and email for fetch
        $moduleBadge = $learningModule->getBadge();
        $email = $user->getEmail();

        //give the badge to email
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', self::BADGR_API.'/v2/badgeclasses/' . $moduleBadge . '/assertions', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
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

    public function getAllBadges($badges, User $user, string $accessToken): array
    {
        //get mail from user
        $email = $user->getEmail();
        $userBadges = [];

        //var_dump($badges);

        //get badges from email user
        $httpClient = HttpClient::create();
        foreach ($badges as &$badgeKey) {
            $response = $httpClient->request('GET', self::BADGR_API.'/v2/badgeclasses/' . $badgeKey, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
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