<?php

namespace Prismic\Bundle\PrismicBundle\EventListener;

use Prismic\Bundle\PrismicBundle\Helper\PrismicContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class ContextListener
{
    /**
     * @var PrismicContext
     */
    private $context;

    /**
     * Constructor.
     *
     * @param PrismicContext $context
     */
    public function __construct(PrismicContext $context)
    {
        $this->context = $context;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $this->context->setAccessToken($request->get('ACCESS_TOKEN'));
        $this->context->setRef($request->query->get('ref'));
    }
}
