<?php

namespace Prismic\Bundle\PrismicBundle\Helper;

use Prismic\Api;

class PrismicHelper
{

    private $apiEndpoint;
    private $accessToken;
    private $clientId;
    private $clientSecret;

    public function __construct($apiEndpoint, $accessToken, $clientId, $clientSecret)
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->accessToken = $accessToken;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @param string $customAccessToken
     * @return Api
     */
    public function getApiHome($customAccessToken = null)
    {
        return Api::get($this->apiEndpoint, $customAccessToken ? $customAccessToken : $this->accessToken);
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

}
