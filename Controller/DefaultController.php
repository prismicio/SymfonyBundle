<?php

namespace Prismic\Bundle\PrismicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
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

    public function detailAction($id, $slug)
    {
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
                    $this->generateUrl('detail', array('id' => $id, 'slug' => $doc->slug(), 'ref' => $ctx->getMaybeRef()))
                );
            }

        }

        throw $this->createNotFoundException('Document not found');
    }

    public function searchAction(Request $request)
    {
        $q = $request->query->get('q');
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

}
