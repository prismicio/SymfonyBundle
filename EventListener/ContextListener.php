<?php

namespace Prismic\Bundle\PrismicBundle\EventListener;

use Prismic;
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
        if (false === $event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        $previewCookie = str_replace('.', '_', Prismic\PREVIEW_COOKIE);
        $experimentsCookie = str_replace('.', '_', Prismic\EXPERIMENTS_COOKIE);

        if ($request->cookies->has($previewCookie)) {
            $newRef = $request->cookies->get($previewCookie);
        } else if ($request->cookies->has($experimentsCookie)) {
            $cookie = $request->cookies->get($experimentsCookie);
            $newRef = $this->context->getApi()->getExperiments()->refFromCookie($cookie);
        }
        
        isset($newRef) and $this->context->setRef($newRef);
    }

}
