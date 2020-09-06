<?php


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
        $configResolver = $this->getConfigResolver();
        $queryType = $this->getQueryTypeRegistry()->getQueryType('App:CustomSearch');

        $searchText = trim($request->query->get('searchText', ''));

        if (empty($searchText)) {
            return $this->render(
                "@ezdesign/search/custom_search.html.twig",
                [
                    'search_text' => '',
                    'pager' => null,
                ]
            );
        }

        $pager = new Pagerfanta(
            new FindAdapter(
                $queryType->getQuery(['search_text' => $searchText]),
                $this->getSite()->getFindService()
            )
        );

        $pager->setNormalizeOutOfRangePages(true);
        $pager->setMaxPerPage((int) $configResolver->getParameter('search.default_limit', 'ngsite'));

        $currentPage = $request->query->getInt('page', 1);
        $pager->setCurrentPage($currentPage > 0 ? $currentPage : 1);

        return $this->render(
            "@ezdesign/search/custom_search.html.twig",
            [
                'search_text' => $searchText,
                'pager' => $pager,
            ]
        );
    }
}
