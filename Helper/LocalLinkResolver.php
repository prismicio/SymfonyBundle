<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Prismic\LinkResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @var string
     */
    private $routeName;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param Api $api
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, $routeName)
    {
        $this->urlGenerator = $urlGenerator;
        $this->routeName = $routeName;
    }

    /**
     * @param \Prismic\Fragment\Link\DocumentLink $link
     *
     * @return string
     */
    public function resolve($link)
    {
        return $this->urlGenerator->generate(
            $this->routeName,
            [
                'id' => $link->getId(),
                'slug' => $link->getSlug(),
            ]
        );
    }

}
