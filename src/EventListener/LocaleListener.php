<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LocaleListener
{
    private $defaultLocale;
    private $session;
    private $request;

    public function __construct($defaultLocale = 'en', SessionInterface $session,Request $request)
    {
        $this->defaultLocale = $defaultLocale;
        $this->session = $session;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $locale = $this->session->get('_locale', $this->defaultLocale);
        
        $request->setLocale($locale);
    }
    
}
