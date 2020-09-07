# eZ Platform document field mappers

## Installation

## Tutorial

### The requirement

Our client wants to improve the article search by enriching the article documents in solr with `title` and `caption` fields from their child images.
The specification is the following:
- create document field mapper for `ng_article` content type
- mapper must process the article's child content items of type `image`
- data stored in `image` fields `title` and `caption` should be added to `article` full-text index

Next, should then create a custom search page for articles.


### The solution

[ArticleChildrenMapper](src/AppBundle/Query/Index/ArticleChildrenMapper.php)

```yaml
app.index.article.content_field:
    class: AppBundle\Query\Index\ArticleChildrenMapper
    arguments:
        - '@ezpublish.spi.persistence.content_handler'
        - '@ezpublish.spi.persistence.location_handler'
        - '@ezpublish.spi.persistence.content_type_handler'
    tags:
        - { name: ezpublish.search.solr.field_mapper.content }
```

[SearchController](src/AppBundle/Controller/SearchController.php)

[SearchQueryType](vendor/netgen/site-bundle/bundle/QueryType/SearchQueryType.php)

```yaml
app.controller.search:
    class: AppBundle\Controller\SearchController
    parent: netgen.ezplatform_site.controller.base
```

```yaml
app.route.article.search:
    path: /article/search
    defaults:
        _controller: "app.controller.search"
    methods: [GET]
```

[article_search.html.twig](src/AppBundle/Resources/views/themes/app/search/article_search.html.twig)
