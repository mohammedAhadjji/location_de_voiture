<?php
namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LocaleListener
{
    private string $defaultLocale;
    private SessionInterface $session;
    private LoggerInterface $logger ;

    public function __construct(string $defaultLocale = 'en', SessionInterface $session, LoggerInterface $logger)
    {
        $this->defaultLocale = $defaultLocale;
        $this->session = $session;
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$event->isMainRequest()) {
            return;
        }

        $locale = $this->session->get('_locale', $this->defaultLocale);
       // $this->logger->info("Setting locale to". $locale ."for request: {$request->getPathInfo()}");
        $request->setLocale($locale);
    }
}
