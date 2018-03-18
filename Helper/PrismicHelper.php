<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use GuzzleHttp\ClientInterface;
use Ivory\HttpAdapter\HttpAdapterInterface;
use Prismic\Api;
use Prismic\Cache\CacheInterface;

class PrismicHelper
{
    /**
     * @var string
     */
    private $apiEndpoint;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var HttpAdapterInterface|null
     */
    private $client;

    /**
     * @var CacheInterface|null
     */
    private $cache;

    /**
     * Constructor.
     *
     * @param string               $apiEndpoint
     * @param string               $accessToken
     * @param string               $clientId
     * @param string               $clientSecret
     * @param HttpAdapterInterface $client
     * @param CacheInterface       $cache
     */
    public function __construct(
        $apiEndpoint,
        $accessToken,
        $clientId,
        $clientSecret,
        ClientInterface $client = null,
        CacheInterface $cache = null
    ) {
        $this->apiEndpoint = $apiEndpoint;
        $this->accessToken = $accessToken;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * @return String API Endpoint
     */
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }

    /**
     * @param string $customAccessToken
     * @return Api
     */
    public function getApiHome($customAccessToken = null)
    {
        return Api::get(
            $this->apiEndpoint,
            $customAccessToken ? $customAccessToken : $this->accessToken,
            $this->client,
            $this->cache
        );
    }

    /**
     * @return null|CacheInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return HttpAdapterInterface|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

}
