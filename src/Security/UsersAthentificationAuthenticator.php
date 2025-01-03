<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UsersAthentificationAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private const LOGIN_ADMIN_ROUTE = 'app_login_admin';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $currentRoute = $request->getPathInfo();
        $user = $token->getUser();
        
        // Check if the user is trying to access an admin route (starting with /admin)
        if (strpos($currentRoute, '/admin') === 0) {
            // If the user doesn't have the ROLE_ADMIN, redirect to admin login
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('app_login_admin'));
            }
    
            // If the user is admin, redirect to the admin dashboard or appropriate route
            return new RedirectResponse($this->urlGenerator->generate('admin_'));
        }
    
        // If the user is authenticated and the target path exists, redirect to that
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
    
        // Default redirect to the user's dashboard
        return new RedirectResponse($this->urlGenerator->generate('app_user_dashbord'));
    }

    

    protected function getLoginUrl(Request $request): string
    {
        $currentRoute = $request->getPathInfo();
        if (strpos($currentRoute, '/admin') === 0) {
            return $this->urlGenerator->generate(self::LOGIN_ADMIN_ROUTE);
        }else{
           return $this->urlGenerator->generate(self::LOGIN_ROUTE);
        }
       
    }
}
