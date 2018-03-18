<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Prismic\Api;
use Prismic\Document;
use Prismic\Fragment\Link\DocumentLink;
use Prismic\LinkResolver;
use Prismic\Ref;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class PrismicContext
 *
 * @package Prismic\Bundle\PrismicBundle\Helper
 */
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
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(PrismicHelper $prismic, UrlGeneratorInterface $urlGenerator, LinkResolver $linkResolver)
    {
        $this->prismic = $prismic;
        $this->urlGenerator = $urlGenerator;
        $this->linkResolver = $linkResolver;
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
    }

    /**
     * @param string $ref
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
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
     * @return LinkResolver
     */
    public function getLinkResolver()
    {
        return $this->linkResolver;
    }

    /**
     * @param Document $doc
     *
     * @return string
     */
    public function resolveLink(Document $doc)
    {
        $link = new DocumentLink(
            $doc->getId(),
            $doc->getUid(),
            $doc->getType(),
            $doc->getTags(),
            $doc->getSlug(),
            $doc->getFragments(),
            false
        );

        return $this->getLinkResolver()->resolve($link);
    }

    /**
     * @param string $id
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

    /**
     * Get redirect response for preview session
     *
     * @param string $token
     * @param string $defaultUrl
     * @return RedirectResponse
     */
    public function previewSession($token, $defaultUrl)
    {
        $url = $this->getApi()->previewSession($token, $this->getLinkResolver(), '/');
        $response = new RedirectResponse($url);
        $response->headers->setCookie(new Cookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false));

        return $response;
    }

}
