<?php

namespace Prismic\Bundle\PrismicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OAuthController extends Controller
{
    public function signinAction()
    {
        $prismic = $this->get('prismic.helper');

        return $this->redirect(
            $prismic->getApiHome()->oauthInitiateEndpoint().'?'.http_build_query(
                array(
                    "client_id" => $prismic->getClientId(),
                    "redirect_uri" => $this->generateUrl('auth_callback', array(), true),
                    "scope" => "master+releases"
                )
            )
        );
    }

    public function callbackAction(Request $request)
    {
        $prismic = $this->get('prismic.helper');

        $data = array(
            "grant_type" => "authorization_code",
            "code" => $request->query->get('code'),
            "redirect_uri" => $this->generateUrl('auth_callback', array(), true),
            "client_id" => $prismic->getClientId(),
            "client_secret" => $prismic->getClientSecret()
        );

        $conn = curl_init();
        curl_setopt($conn, CURLOPT_URL, $prismic->getApiHome()->oauthTokenEndpoint());
        curl_setopt($conn, CURLOPT_POST, true);
        curl_setopt($conn, CURLOPT_POSTFIELDS, $data);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);

        $accessToken = json_decode(curl_exec($conn))->{'access_token'};

        $request->getSession()->set('ACCESS_TOKEN', $accessToken);

        return $this->redirect(
            $this->generateUrl('home')
        );
    }

    public function signoutAction(Request $request)
    {
        $request->getSession()->clear();

        return $this->redirect(
            $this->generateUrl('home')
        );
    }

}
