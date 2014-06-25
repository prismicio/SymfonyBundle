<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Symfony\Component\Routing\RouterInterface;

use Prismic\api;
use Prismic\Ref;
use Prismic\Document;
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
    private $linkResolver;

    /**
     * @param PrismicHelper $prismic
     * @param RouterInterface $router
     */
    public function __construct(PrismicHelper $prismic, RouterInterface $router)
    {
        $this->prismic = $prismic;
        $this->router = $router;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        $this->api = $this->linkResolver = null;
    }

    /**
     * @param string $ref
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        $this->maybeRef = $this->linkResolver = null;
    }

    /**
     * @return bool
     */
    public function hasPrivilegedAccess()
    {
        return isset($this->accessToken);
    }

    /**
     * @return Api
     */
    public function getApi()
    {
        if (!$this->api) {
            $this->api = $this->prismic->getApiHome($this->accessToken);
        }

        return $this->api;
    }

    /**
     * @return Ref
     */
    public function getRef()
    {
        if (null === $this->ref) {
            $this->ref = $this->getMasterRef();
        }

        return $this->ref;
    }

    /**
     * @return Ref
     */
    public function getMasterRef()
    {
        if (null === $this->masterRef) {
            $this->masterRef = $this->getApi()->master()->getRef();
        }

        return $this->masterRef;
    }

    /**
     * @return Ref
     */
    public function getMaybeRef()
    {
        if (!$this->maybeRef) {
            $this->maybeRef = (string) $this->getRef() === (string) $this->getMasterRef() ? null : $this->getRef();
        }

        return $this->maybeRef;
    }

    /**
     * @return LocalLinkResolver
     */
    public function getLinkResolver()
    {
        if (!$this->linkResolver) {
            $this->linkResolver = new LocalLinkResolver($this->router, $this->getApi(), $this->getMaybeRef());
        }

        return $this->linkResolver;
    }

    /**
     * @param Document $doc
     *
     * @return string
     */
    public function resolveLink(Document $doc)
    {
        $link = new DocumentLink($doc->getId(), $doc->getType(), $doc->getTags(), $doc->getSlug(), false);

        return $this->getLinkResolver()->resolve($link);
    }

    /**
     * @param $id
     *
     * @return Document|null
     */
    public function getDocument($id) 
    {
        $docs = $this->getApi()->forms()->everything->ref($this->getRef())->query(
                '[[:d = at(document.id, "'.$id.'")]]'
            )
            ->submit()
        ;

        if (is_array($docs->getResults()) && count($docs->getResults()) > 0) {
            return $docs->getResults()[0];
        }

        return null;
    }

}
