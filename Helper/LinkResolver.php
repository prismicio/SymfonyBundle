<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Symfony\Component\Routing\RouterInterface;

use Prismic\Api;
use Prismic\Ref;
use Prismic\LinkResolver as BaseLinkResolver;
use Prismic\Fragment\Link\DocumentLink;

abstract class LinkResolver extends BaseLinkResolver
{
    private $maybeRef;

    /**
     * @return string|null
     */
    public function getMaybeRef($maybeRef)
    {
        $this->maybeRef = $maybeRef;
    }

    /**
     * @param string|null $maybeRef
     */
    public function setMaybeRef($maybeRef)
    {
        $this->maybeRef = $maybeRef;
    }
}
