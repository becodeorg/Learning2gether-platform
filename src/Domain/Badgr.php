<?php

declare(strict_types=1);

namespace App\Domain;

use App\Entity\LearningModule;
use App\Entity\User;
use Symfony\Component\HttpClient\HttpClient;

class Badgr
{
    // TODO add these constants to a config file
    // private const BADGR_PASSWORD = 'InCodeWeTrust!';
    // private const BADGR_USERNAME = 'learning2gether@becode.org';
    private const BADGR_PASSWORD = 'Jeanne001';
    private const BADGR_USERNAME = 'alexandre@becode.org';
    private const BADGR_API = 'https://api.badgr.io';

    private $refreshToken;
    private $accessToken;

    public function __construct()
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

        $this->refreshToken = $tokenData['refresh_token'];
        $this->accessToken = $tokenData['access_token'];
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

        $this->refreshToken = $tokenData['refresh_token'];
        $this->accessToken = $tokenData['access_token'];

        return $tokenData;
    }

    public function addBadgeToUser(LearningModule $learningModule, User $user): void
    {
        //give the badge to email
        $httpClient = HttpClient::create();
        $response = $httpClient->request('POST', self::BADGR_API . '/v2/badgeclasses/' . $learningModule->getBadge() . '/assertions', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
            'json' => [
                'recipient' => [
                    'type' => 'email',
                    'hashed' => false,
                    'identity' => $user->getEmail()
                ]
            ]
        ]);

        $user->addBadge($learningModule);
    }

    public function checkIfBadgeExists($badgeKey)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', self::BADGR_API . '/v2/badgeclasses/' . $badgeKey, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ]
        ]);

        if (200 !== $response->getStatusCode()) {
            // handle the HTTP request error (e.g. retry the request)
            return $response->getContent();
        } else {
            return true;
        }
    }

    public function getAllBadges(array $badges, User $user): array
    {
        //get mail from user
        $email = $user->getEmail();
        $userBadges = [];

        //get badges from email user
        $httpClient = HttpClient::create();
        foreach ($badges as &$badgeKey) {
            $response = $httpClient->request('GET', self::BADGR_API . '/v2/badgeclasses/' . $badgeKey, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
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

    public function getImage(User $user, string $badgeHash)
    {
        $badge = $this->getAllBadges([$badgeHash], $user);
        return $badge[0]['result'][0]['image'];
    }
}
