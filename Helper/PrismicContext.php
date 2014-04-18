<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Symfony\Component\Routing\Router;

use Prismic\Fragment\Link\DocumentLink;

class PrismicContext
{
    private $prismic;
    private $router;

    private $accessToken;
    private $api;
    private $masterRef;
    private $ref;
    private $maybeRef;

    public function __construct(PrismicHelper $prismic, Router $router)
    {
        $this->prismic = $prismic;
        $this->router = $router;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    public function hasPrivilegedAccess()
    {
        return isset($this->accessToken);
    }

    public function getApi()
    {
        if (!$this->api) {
            $this->api = $this->prismic->getApiHome($this->accessToken);
        }

        return $this->api;
    }

    public function getRef()
    {
        if (null === $this->ref) {
            $this->ref = $this->getMasterRef();
        }

        return $this->ref;
    }

    public function getMasterRef()
    {
        if (null === $this->masterRef) {
            $this->masterRef = $this->getApi()->master()->getRef();
        }

        return $this->masterRef;
    }

    public function getMaybeRef()
    {
        if (!$this->maybeRef) {
            $this->maybeRef = $this->getRef() === $this->getMasterRef() ? null : $this->getRef();
        }

        return $this->maybeRef;
    }

    public function linkResolver() 
    {
        return new LocalLinkResolver($this->router, $this->getApi(), $this->getMaybeRef());
    }

    public function resolveLink($doc) 
    {
        $link = new DocumentLink($doc->getId(), $doc->getType(), $doc->getTags(), $doc->getSlug(), false);

        return $this->linkResolver()->resolve($link);
    }

    public function getDocument($id) 
    {
        $docs = $this->getApi()->forms()->everything->ref($this->getRef())->query(
                '[[:d = at(document.id, "'.$id.'")]]'
            )
            ->submit()
        ;

        if (is_array($docs) && count($docs) > 0) {
            return $docs[0];
        }

        return null;
    }

}
