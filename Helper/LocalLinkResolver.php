<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Prismic\Ref;
use Prismic\Fragment\Link\DocumentLink;

class LocalLinkResolver extends LinkResolver
{
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param Api $api
     * @param string|null $maybeRef
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param DocumentLink $link
     * @return string
     */
    public function resolve($link)
    {
        return $this->urlGenerator->generate('detail', array('id' => $link->getId(), 'slug' => $link->getSlug(), 'ref' => (string) $this->getMaybeRef()));
    }
}
