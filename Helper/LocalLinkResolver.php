<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Symfony\Component\Routing\RouterInterface;

use Prismic\Api;
use Prismic\Ref;
use Prismic\LinkResolver;
use Prismic\Fragment\Link\DocumentLink;

class LocalLinkResolver extends LinkResolver
{

    private $router; 
    private $api;
    private $maybeRef;

    /**
     * @param RouterInterface $router
     * @param Api $api
     * @param string|null $maybeRef
     */
    public function __construct(RouterInterface $router, Api $api, $maybeRef = null)
    {
        $this->router = $router;
        $this->api = $api;
        $this->maybeRef = $maybeRef;
    }

    /**
     * @param DocumentLink $link
     * @return string
     */
    public function resolve($link) 
    {
        return $this->router->generate('detail', array('id' => $link->getId(), 'slug' => $link->getSlug(), 'ref' => (string) $this->maybeRef));
    }

}
