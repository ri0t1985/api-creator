- User authentication.
We currently dont have support for user authentication, so the API is always publicly accessible, and we cant apply rate limiting.
The same is true for the routes created, which are also always accessible. 

- Caching
Nothing is cached, and the websites are scraped everytime an endpoint is called

- Supporting retrieval of properties
We currently dont support the handling of properties, which can be a problem. For instance, you can return 

- Error handling and validation
Error handling needs to be improved, since SQL errors are returned through the JSON response in most cases. 
Also, we dont validate a lot of things, like if the website already exists.