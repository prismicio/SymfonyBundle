# PrismicBundle

This Bundle integrates the http://prismic.io php-kit with the Symfony Framework:
https://github.com/prismicio/php-kit

For an example use see:
https://github.com/prismicio/php-symfony-starter

## Installation

Add the following dependencies to your projects ``composer.json`` file:

    "require": {
        # ..
        "prismic/prismic-bundle": "~1.0@dev"
        # ..
    }

## Configuration

Full default configuration for bundle:

```yaml
prismic:
  api:
    endpoint:               ~      # Required
    access_token:           ~
    client_id:              ~
    client_secret:          ~
  oauth:
    redirect_route:         home   # Name of the route
    redirect_route_params:  []     # An array with additional route params
  cache:                    true   # Default apc built-in cache
  link_resolver_route:      detail # Name of the route
```

## LinkResolver Customization

You can override `prismic.link_resolver_route` parameter with route name to handle link resolver.
This route can have `$id` or `$slug` parameter to find document.
If you want to implement custom logic for your LinkResolver you can override service `prismic.link_resolver`.

## TODOs

- [x] Add a listener for Symfony 2.3 to set the request data into the context as 2.3 does not support ExpressionLanguage
- [ ] Add unit (and functional?) tests
- [ ] Provide twig templates to render documents
- [ ] Make caching configurable once https://github.com/prismicio/php-kit/issues/32 is implemented

## Credits

Kudos to [lsmith77](https://github.com/lsmith77) who did all the hard work!
