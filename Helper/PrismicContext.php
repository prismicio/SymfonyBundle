<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Prismic\api;
use Prismic\Ref;
use Prismic\Document;
use Prismic\Fragment\Link\DocumentLink;

class PrismicContext
{
    private $prismic;
    private $urlGenerator;

    private $accessToken;
    private $api;
    private $ref;
    private $linkResolver;

    /**
     * @param PrismicHelper $prismic
     * @param RouterInterface $router
     */
    public function __construct(PrismicHelper $prismic, UrlGeneratorInterface $urlGenerator)
    {
        $this->prismic = $prismic;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return PrismicHelper
     */
    public function getHelper()
    {
        return $this->prismic;
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

        $this->linkResolver = null;
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
        return $this->getApi()->master()->getRef();
    }

    /**
     * @return UrlGeneratorInterface
     */
    public function getUrlGenerator()
    {
        return $this->urlGenerator;
    }

    /**
     * @return LocalLinkResolver
     */
    public function getLinkResolver()
    {
        if (!$this->linkResolver) {
            $this->linkResolver = new LocalLinkResolver($this->urlGenerator, $this->getApi());
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
            $results = $docs->getResults();
            return $results[0];
        }

        return null;
    }

}
