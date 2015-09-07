<?php

namespace Prismic\Bundle\PrismicBundle\Controller;

use Prismic;
use Prismic\Bundle\PrismicBundle\Helper\PrismicContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 *
 * @package Prismic\Bundle\PrismicBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        /** @var PrismicContext $ctx */
        $ctx = $this->get('prismic.context');
        $docs = $ctx->getApi()->forms()->everything->ref($ctx->getRef())
            ->pageSize(10)
            ->page($request->query->get('page', 1))
            ->submit();

        return $this->render('PrismicBundle:Default:index.html.twig', array(
            'ctx' => $ctx,
            'docs' => $docs
        ));
    }

    /**
     * @param string $id
     * @param string $slug
     *
     * @return RedirectResponse|Response
     *
     * @throws NotFoundHttpException
     */
    public function detailAction($id, $slug)
    {
        /** @var PrismicContext $ctx */
        $ctx = $this->get('prismic.context');
        $doc = $ctx->getDocument($id);

        if ($doc) {
            if ($doc->getSlug() == $slug) {
                return $this->render('PrismicBundle:Default:detail.html.twig', array(
                    'ctx' => $ctx,
                    'doc' => $doc
                ));
            }

            if (in_array($slug, $doc->getSlugs())) {
                return $this->redirect(
                    $this->generateUrl('detail', array('id' => $id, 'slug' => $doc->getSlug()))
                );
            }

        }

        throw $this->createNotFoundException('Document not found');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $q = $request->query->get('q');
        /** @var PrismicContext $ctx */
        $ctx = $this->get('prismic.context');
        $docs = $ctx->getApi()->forms()->everything->ref ($ctx->getRef())->query(
                '[[:d = fulltext(document, "'.$q.'")]]'
            )
            ->pageSize(10)
            ->page($request->query->get('page', 1))
            ->submit();

        return $this->render('PrismicBundle:Default:search.html.twig', array(
            'ctx' => $ctx,
            'docs' => $docs
        ));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function previewAction(Request $request)
    {
        $token = $request->query->get('token');
        /** @var PrismicContext $ctx */
        $ctx = $this->get('prismic.context');
        $url = $ctx->getApi()->previewSession($token, $ctx->getLinkResolver(), '/');
        $response = new RedirectResponse($url);
        $response->headers->setCookie(new Cookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false));
        return $response;
    }

}
