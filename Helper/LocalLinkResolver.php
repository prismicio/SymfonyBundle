<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Prismic\Api;
use Prismic\Ref;
use Prismic\LinkResolver;
use Prismic\Fragment\Link\DocumentLink;

/**
 * Class LocalLinkResolver
 *
 * @package Prismic\Bundle\PrismicBundle\Helper
 */
class LocalLinkResolver extends LinkResolver
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var Api
     */
    private $api;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param Api $api
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, Api $api)
    {
        $this->urlGenerator = $urlGenerator;
        $this->api = $api;
    }

    /**
     * @param DocumentLink $link
     *
     * @return string
     */
    public function resolve($link)
    {
        return $this->urlGenerator->generate('detail', ['id' => $link->getId(), 'slug' => $link->getSlug()]);
    }
}
