<?php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LocaleListener
{
    private string $defaultLocale;
    private SessionInterface $session;

    public function __construct(string $defaultLocale = 'en', SessionInterface $session)
    {
        $this->defaultLocale = $defaultLocale;
        $this->session = $session;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Only process the master request (avoid sub-requests)
        if (!$event->isMainRequest()) {
            return;
        }

        // Set locale from session or fallback to default
        $locale = $this->session->get('_locale', $this->defaultLocale);
        $request->setLocale($locale);
    }
}
