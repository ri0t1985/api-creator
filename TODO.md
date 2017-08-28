# TODO

Being a project which was setup in a short 4 days, it's only logical to have 
a lot of wishes for features to come.

## User authentication.
We currently dont have support for user authentication, so the API is always publicly accessible, and we cant apply rate limiting.
The same is true for the routes created, which are also always accessible. 

## Caching
Nothing is cached, and the websites are scraped everytime an endpoint is called
We should be also be caching HTTP headers to check if the content has changed.

## Supporting retrieval of properties
We currently dont support the handling of properties, which can be a problem. For instance, you can return 

## Error handling and validation
Error handling needs to be improved, since SQL errors are returned through the JSON response in most cases. 
Also, we dont validate a lot of things, like if the website already exists.

- Support parsing options:
The following come to mind:
    - support whether or not to strip the HTML from the item
    - support if a property should be called (img->src for instance)
    - support to convert URLs to exact urls
    - support property instead of content
    
## Implement Tiny proxy to prevent blacklisting
Support for several proxy servers.

## Implement different selector types
Xpath, Regex


