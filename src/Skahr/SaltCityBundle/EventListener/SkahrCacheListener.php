<?php
namespace Skahr\SaltCityBundle\EventListener;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Session\Session;

class SkahrCacheListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
		$session = new Session();
		if($session->get('login'))
		{$response = $event->getResponse();

        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('max-age', 0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
		$response->headers->addCacheControlDirective('no-store', true);
		}
    }
}