<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractController
{
    #[Route('/change-language', name: 'change_language', methods: ['POST'])]
    public function changeLanguage(Request $request, SessionInterface $session)
    {
        // CSRF token validation
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('change_language', $token)) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        // Get the selected locale from the form
        $locale = $request->request->get('currency_name');
        
        // Set the selected locale in the session
        $session->set('_locale', $locale);
       // dd($session->get('_locale')); 
        // Redirect back to the referring page
        return $this->redirect($request->headers->get('referer'));
    }
}
