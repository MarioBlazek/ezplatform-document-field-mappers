<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use Netgen\Bundle\EzPlatformSiteApiBundle\Controller\Controller;
use Netgen\EzPlatformSiteApi\Core\Site\Pagination\Pagerfanta\FindAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $queryType = $this->getQueryTypeRegistry()->getQueryType('NetgenSite:Search');

        $searchText = trim($request->query->get('searchText', ''));

        if (empty($searchText)) {
            return $this->render(
                "@ezdesign/search/article_search.html.twig",
                [
                    'search_text' => '',
                    'pager' => null,
                ]
            );
        }

        $query = $queryType->getQuery([
            'search_text' => $searchText,
            'content_types' => ['ng_article'],
        ]);

        $pager = new Pagerfanta(
            new FindAdapter(
                $query,
                $this->getSite()->getFindService()
            )
        );

        $pager->setNormalizeOutOfRangePages(true);
        $pager->setMaxPerPage((int) 12);

        $currentPage = $request->query->getInt('page', 1);
        $pager->setCurrentPage($currentPage > 0 ? $currentPage : 1);

        return $this->render(
            "@ezdesign/search/article_search.html.twig",
            [
                'search_text' => $searchText,
                'pager' => $pager,
            ]
        );
    }
}
