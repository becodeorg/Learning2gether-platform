<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    const ONE_YEAR = 60 * 60 * 24 * 365;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        $this->changeLanguageCookie($request);

        //redirect the user to either portal or the partner homepage
        $routeName = 'portal';
        if ($token->getUser()->isPartner()) {
            $routeName = 'partner';
        }

        return new RedirectResponse($this->urlGenerator->generate($routeName));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }

    private function changeLanguageCookie(Request $request): void
    {
        // $_SESSION['_sf2_attributes']['_security.last_username']; // string, used to login
        // find the user from the database who has this email
        // then from that user, get language -> get code
        if (!isset($_SESSION['_sf2_attributes']['_security.last_username'])) {
            //user just registered so we don't have the username yet
            //but it doesn't matter because we configured his language based on his cookie anyway.
            return;
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $_SESSION['_sf2_attributes']['_security.last_username']]);
        if (is_null($user)) {
            $userLangCode = 'en';
        } else {
            $userLangCode = mb_strtolower($user->getLanguage()->getCode());
        }

        $request->setLocale($userLangCode);
        setcookie('language', $userLangCode, time() + self::ONE_YEAR, '/', $_SERVER['HTTP_HOST']);
    }
}
