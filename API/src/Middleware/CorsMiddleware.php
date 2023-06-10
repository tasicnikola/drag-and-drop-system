<?php

namespace App\Middleware;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsMiddleware implements EventSubscriberInterface
{
    public function corsHeaders(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $params['origin'] = $event->getRequest()->headers->get('origin') ? : '*';

        foreach ($this->getCorsHeaders($params) as $key => $value) {
            $response->headers->set($key, $value);
        }

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => [
                ['corsHeaders'],
            ]
        ];
    }

    private function getCorsHeaders(array $params): array
    {
        return [
            'Access-Control-Allow-Origin' => $params['origin'],
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Max-Age' => 0,
            'Access-Control-Allow-Methods' => 'GET, PUT, POST, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' =>
                'Origin, X-Requested-With, Content-Type, Cache-Control, ApiKey, Pragma, AccessToken',
        ];
    }
}
