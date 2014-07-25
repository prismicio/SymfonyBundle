<?php

namespace Prismic\Bundle\PrismicBundle\Controller;

use Prismic\Bundle\PrismicBundle\Helper\PrismicHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OAuthController
{
    /**
     * @var PrismicHelper
     */
    private $prismic;

    /**
     * @var string
     */
    private $redirectRoute;

    /**
     * @var array
     */
    private $redirectRouteParams;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * Constructor.
     *
     * @param PrismicHelper         $prismic
     * @param UrlGeneratorInterface $urlGenerator
     * @param string                $redirectRoute
     * @param array                 $redirectRouteParams
     */
    public function __construct(PrismicHelper $prismic, UrlGeneratorInterface $urlGenerator, $redirectRoute, array $redirectRouteParams = array())
    {
        $this->prismic = $prismic;
        $this->urlGenerator = $urlGenerator;
        $this->redirectRoute = $redirectRoute;
        $this->redirectRouteParams = $redirectRouteParams;
    }

    public function signinAction()
    {
        return new RedirectResponse($this->prismic->getApiHome()->oauthInitiateEndpoint().'?'.http_build_query(
            array(
                'client_id' => $this->prismic->getClientId(),
                'redirect_uri' => $this->urlGenerator->generate('auth_callback', array(), true),
                'scope' => "master+releases"
            )
        ));
    }

    public function callbackAction(Request $request)
    {
        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $request->query->get('code'),
            'redirect_uri' => $this->urlGenerator->generate('auth_callback', array(), true),
            'client_id' => $this->prismic->getClientId(),
            'client_secret' => $this->prismic->getClientSecret()
        );

        $conn = curl_init();
        curl_setopt($conn, CURLOPT_URL, $this->prismic->getApiHome()->oauthTokenEndpoint());
        curl_setopt($conn, CURLOPT_POST, true);
        curl_setopt($conn, CURLOPT_POSTFIELDS, $data);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);

        $accessToken = json_decode(curl_exec($conn))->{'access_token'};

        $request->getSession()->set('ACCESS_TOKEN', $accessToken);

        return new RedirectResponse($this->urlGenerator->generate(
            $this->redirectRoute,
            $this->redirectRouteParams
        ));
    }

    public function signoutAction(Request $request)
    {
        $request->getSession()->clear();

        return new RedirectResponse($this->urlGenerator->generate(
            $this->redirectRoute,
            $this->redirectRouteParams
        ));
    }

}
