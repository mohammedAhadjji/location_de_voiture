<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractController
{
    #[Route('/change-language', name: 'change_language', methods: ['POST'])]
    public function changeLanguage(Request $request, SessionInterface $session)
    {
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('change_language', $token)) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $locale = $request->request->get('currency_name');

        $session->set('_locale', $locale);

        $request->setLocale($locale);

        return $this->redirect($request->headers->get('referer', $this->generateUrl('app_home')));
    }
}
