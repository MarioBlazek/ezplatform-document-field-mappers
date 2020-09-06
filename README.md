# eZ Platform document field mappers

## Installation

## Tutorial

### The requirement

Our client wants to improve the search for an article by enriching the article documents in solr with `title` and `caption` fields from their child images.
The specification is the following:
- create document field mapper for `ng_article` content type
- mapper must process the article's child content items of type `image`
- data stored in `image` fields `title` and `caption` should be added to `article` full-text index

Next, should then create a custom search page for articles.
