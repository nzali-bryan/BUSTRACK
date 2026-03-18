<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AdministrateurAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_administrateur_index';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport

    {
        // Par defaut de l'authentificator 
        // $nom = $request->getPayload()->getString('Nom');

        // $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $nom);

        // return new Passport(
        //     new UserBadge($nom),
        //     new PasswordCredentials($request->getPayload()->getString('password')),
        //     [
        //         new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
        //         new RememberMeBadge(),
        //     ]
        // );
            // Nom des champs dans ton formulaire HTML
        $nom = $request->request->get('nom');          // name="nom"
        $password = $request->request->get('password'); // name="password"

        // Sauvegarde pour pré-remplir en cas d'échec
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $nom);

        return new Passport(
            new UserBadge($nom),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
             $user = $token->getUser();
            
         if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('app_administrateur'));
        } elseif (in_array('ROLE_CONDUCTEUR', $user->getRoles())) {
            return new RedirectResponse($this->urlGenerator->generate('conducteur_dashboard'));
        }
  
        return new RedirectResponse($this->urlGenerator->generate('app_administrateur'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}

