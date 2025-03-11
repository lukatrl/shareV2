<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class CaptchaAuthentificator extends AbstractAuthenticator
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request)
    {
        $captchaUserInput = $request->request->get('captcha');
        $captchaSolution = $request->request->get('captcha_solution');

        if (!$captchaUserInput || !$captchaSolution || (int)$captchaUserInput !== (int)$captchaSolution) {
            throw new AuthenticationException('Captcha incorrect.');
        }

        // Récupération de l'email pour permettre une authentification correcte
        $email = $request->request->get('email');

        return new SelfValidatingPassport(new UserBadge($email));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $url = $this->urlGenerator->generate('app_login');
        $request->getSession()->getFlashBag()->add('error', 'Captcha incorrect.');
        return new RedirectResponse($url);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('index')); // Redirection vers la page d'accueil
    }
}