<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    public const ROUTE_LOGIN = 'app_admin_login';

    private UrlGeneratorInterface $urlGenerator;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request): bool
    {
        return self::ROUTE_LOGIN === $request->attributes->get('_route')
            && $request->isMethod('post');
    }

    public function getCredentials(Request $request): array
    {
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $request->request->get('username')
        );

        return [
            'username' => $request->request->get('login')['username'],
            'password' => $request->request->get('login')['password'],
            'csrf_token' => $request->request->get('login')['_token'],
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        try {
            return $userProvider->loadUserByUsername($credentials['username']);
        } catch (UsernameNotFoundException $exception) {
            throw new CustomUserMessageAuthenticationException('Bad credentials');
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if($this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            return true;
        }

        throw new CustomUserMessageAuthenticationException("Bad credentials");
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse($this->urlGenerator->generate('app_admin_index'));
    }

    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate(self::ROUTE_LOGIN);
    }
}