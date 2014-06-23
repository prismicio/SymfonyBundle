<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Prismic\Api;
use Prismic\Ref;
use Prismic\LinkResolver;
use Prismic\Fragment\Link\DocumentLink;

class LocalLinkResolver extends LinkResolver
{

    private $urlGenerator;
    private $api;
    private $maybeRef;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param Api $api
     * @param string|null $maybeRef
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, Api $api, $maybeRef = null)
    {
        $this->urlGenerator = $urlGenerator;
        $this->api = $api;
        $this->maybeRef = $maybeRef;
    }

    /**
     * @param DocumentLink $link
     * @return string
     */
    public function resolve($link)
    {
        return $this->urlGenerator->generate('detail', array('id' => $link->getId(), 'slug' => $link->getSlug(), 'ref' => (string) $this->maybeRef));
    }

}
