<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Symfony\Component\Routing\RouterInterface;

use Prismic\Api;
use Prismic\Ref;
use Prismic\Fragment\Link\DocumentLink;

class LocalLinkResolver extends LinkResolver
{
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param DocumentLink $link
     * @return string
     */
    public function resolve($link)
    {
        return $this->router->generate('detail', array('id' => $link->getId(), 'slug' => $link->getSlug(), 'ref' => (string) $this->getMaybeRef()));
    }

}
